<?php

namespace App\Jobs;

use Exception;
use Illuminate\Support\Facades\Log;
use Shopify\Clients\Rest;
use App\Http\Repository\ShopifyProductRepository;
use App\Models\ShopifyCollection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncShopifyCollectionProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The instance of the session.
     *
     * @var {session type}
     */
    public $session;


    /**
     * The ID of the collection.
     *
     * @var int
     */
    public $collectionID;


    /**
     * The repository for the Shopify product.
     *
     * @var ShopifyProductRepository
     */
    public $shopifyProductRepository;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($session, $collectionID)
    {
        $this->session = $session;
        $this->collectionID = $collectionID;
        $this->shopifyProductRepository = new ShopifyProductRepository;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $hasNextProductPage = 1;
        $sinceProductID = 0;

        while ($hasNextProductPage > 0) {
            throttleRequestIfNeeded($this->session);

            try {
                $shop = $this->session->getShop();
                $client = new Rest($shop, $this->session->getAccessToken());

                $products = $client->get("collections/$this->collectionID/products.json", [], [
                    'limit' => 250,
                    'since_id' => $sinceProductID
                ]);

                $productHeaders = $products->getHeaders();
                $products = $products->getDecodedBody();

                foreach ($products['products'] as $product) {
                    $this->shopifyProductRepository->updateOrSaveProduct($product, $this->session, $shop, $this->collectionID);
                }

                $collection = ShopifyCollection::where('shopify_collection_id', $this->collectionID)->first();
                $collection->update([
                    'product_count' => $collection->shopifyProducts()->count(),
                ]);

                $sinceProductID = $products['products'][array_key_last($products['products'])]['id'];
                $hasNextProductPage = hasNextPage($productHeaders['link'][0]);
            } catch (Exception $e) {
                Log::error('Error while getting products: ' . $e->getMessage());
            }
        }
    }
}
