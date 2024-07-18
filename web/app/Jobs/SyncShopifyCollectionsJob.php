<?php

namespace App\Jobs;

use App\Http\Repository\ShopifyCollectionRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Shopify\Clients\Rest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncShopifyCollectionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The instance of the ShopifyCollectionRepository class.
     *
     * @var \App\Http\Repository\ShopifyCollectionRepository
     */
    public $shopifyCollectionRepository;


    /**
     * The instance of the session.
     *
     * @var {session type}
     */
    public $session;


    /**
     * The collection type that will be synced.
     *
     * @var string
     */
    public $collection_type;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($session, $collection_type)
    {
        $this->session = $session;
        $this->collection_type = $collection_type;
        $this->shopifyCollectionRepository = new ShopifyCollectionRepository;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $hasNextCollectionPage = 1;
        $sinceID = 0;
        $collection_type = explode('_', $this->collection_type)[0];

        while ($hasNextCollectionPage > 0) {
            throttleRequestIfNeeded($this->session);

            try {
                $shop = $this->session->getShop();
                $client = new Rest($shop, $this->session->getAccessToken());

                $result = $client->get($this->collection_type, [], [
                    'limit' => 250,
                    'since_id' => $sinceID
                ]);

                $headers = $result->getHeaders();
                $result = $result->getDecodedBody();

                foreach ($result[$this->collection_type] as $collection) {
                    $this->shopifyCollectionRepository->updateOrSaveCollection($collection, $this->session, $collection_type);
                    SyncShopifyCollectionProductsJob::dispatch($this->session, $collection['id']);
                }

                $sinceID = $result[$this->collection_type][array_key_last($result[$this->collection_type])]['id'];
                $hasNextCollectionPage = hasNextPage($headers['link'][0]);
            } catch (Exception $e) {
                Log::error('Error while getting products: GetSubscriptionController' . $e->getMessage());
                $hasNextCollectionPage = 0;
            }
        }
    }
}
