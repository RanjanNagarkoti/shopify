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

        $session = Session::where('session_id', $this->session->getId())->first();

        while ($hasNextProductPage > 0) {
            $this->shopifyProductRepository->throttleRequestIfNeeded($this->session);

            try {
                $client = new Rest($this->session->getShop(), $this->session->getAccessToken());
                $result = $client->get('products', [], [
                    'limit' => 250,
                    'since_id' => $since_id
                ]);

                $headers = $result->getHeaders();
                $result = $result->getDecodedBody();

                foreach ($result['products'] as $product) {
                    $shopifyProduct = ShopifyProduct::updateOrCreate(
                        [
                            'shopify_product_id' => $product['id']
                        ],
                        [
                            'session_id' => $session->id,
                            'shopify_product_id' => $product['id'],
                            'title' => $product['title'],
                            'description' => $product['body_html'],
                            'handle' => $product['handle'],
                            'vendor' => $product['vendor'],
                            'product_type' => $product['product_type'],
                            'tags' => $product['tags'],
                            'status' => $product['status'],
                            'image_src' => $product['image'] ? $product['image']['src'] : null
                        ]
                    );

                    foreach ($product['variants'] as $variant) {
                        $options = [
                            'option1' => $variant['option1'] ?? null,
                            'option2' => $variant['option2'] ?? null,
                            'option3' => $variant['option3'] ?? null,
                        ];

                        $shopifyProduct->shopifyProductVariations()->updateOrCreate(
                            [
                                'product_id' => $shopifyProduct['id'],
                                'shopify_variation_id' => $variant['id'],
                            ],
                            [
                                'product_id' => $shopifyProduct['id'],
                                'session_id' => $session->id,
                                'shopify_product_id' => $variant['product_id'],
                                'shopify_variation_id' => $variant['id'],
                                'title' => $variant['title'],
                                'price' => $variant['price'],
                                'sku' => $variant['sku'],
                                'inventory_policy' => $variant['inventory_policy'],
                                'variation_options' => json_encode($options),
                                'image_id' => $variant['image_id'],
                                'inventory_item_id' => $variant['inventory_item_id'],
                                'inventory_quantity' => $variant['inventory_quantity'],
                            ]
                        );
                    }
                }

                $since_id = $result['products'][array_key_last($result['products'])]['id'];
                $hasNextProductPage = $this->shopifyProductRepository->hasNextPage($headers['link'][0]);

            } catch (Exception $e) {
                Log::error('Error while getting products: ' . $e->getMessage());
                $hasNextProductPage = 0;
            }
        }
    }
}
