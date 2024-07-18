<?php

namespace App\Jobs;

use App\Http\Repository\ShopifyProductRepository;
use Shopify\Clients\Rest;
use Exception;
use App\Models\Session;
use App\Models\ShopifyProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportShopifyProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var mixed $session
     */
    public $session;

    
    /**
     * The ShopifyRepository instance.
     *
     * @var \App\Http\Repository\ShopifyProductRepository $shopifyProductRepository
     */
    public $shopifyProductRepository;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($session)
    {
        $this->session = $session;
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
        $since_id = 0;

        while ($hasNextProductPage > 0) {
            throttleRequestIfNeeded($this->session);

            try {
                $shop = $this->session->getShop();
                $client = new Rest($shop, $this->session->getAccessToken());
                $result = $client->get('products', [], [
                    'limit' => 250,
                    'since_id' => $since_id
                ]);

                $headers = $result->getHeaders();
                $result = $result->getDecodedBody();

                foreach ($result['products'] as $product) {
                    $this->shopifyProductRepository->updateOrSaveProductWithVariants($product, $this->session, $shop);
                }

                $since_id = $result['products'][array_key_last($result['products'])]['id'];
                $hasNextProductPage = hasNextPage($headers['link'][0]);
            } catch (Exception $e) {
                Log::error('Error while getting products: ' . $e->getMessage());
                $hasNextProductPage = 0;
            }
        }
    }
}
