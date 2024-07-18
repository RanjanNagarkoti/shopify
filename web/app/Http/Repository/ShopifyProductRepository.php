<?php

namespace App\Http\Repository;

use App\Models\Session;
use App\Models\ShopifyCollection;
use App\Models\ShopifyProduct;

class ShopifyProductRepository
{
    /**
     * Updates or saves a Shopify product.
     *
     * @param array $product The product data.
     * @param \App\Models\Session $session The session object.
     * @param string $shop The Shopify shop URL.
     * @return void
     */
    public function updateOrSaveProduct($product, $session, $shop, $collectionID)
    {
        $session = Session::where('session_id', $session->getId())->first();
        $storeName = explode('.', $shop)[0];

        $shopify_url = "https://admin.shopify.com/store/$storeName/products/";

        $product = ShopifyProduct::updateOrCreate(
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
                'image_src' => $product['image'] ? $product['image']['src'] : null,
                'shopify_url' => $shopify_url . $product['id'],
            ]
        );

        $collection = ShopifyCollection::where('shopify_collection_id', $collectionID)->first();

        if (!$product->shopifyCollections()->where('collection_id', $collection->id)->exists()) {
            $product->shopifyCollections()->attach($collection->id);
        }
    }

    /**
     * Updates or saves a Shopify product with its variants.
     *
     * @param array $product The product data.
     * @param \App\Models\Session $session The session object.
     * @param string $shop The Shopify shop.
     * @return void
     */
    public function updateOrSaveProductWithVariants($product, $session, $shop)
    {
        $session = Session::where('session_id', $session->getId())->first();
        $storeName = explode('.', $shop)[0];

        $shopify_url = "https://admin.shopify.com/store/$storeName/products/";

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
                'image_src' => $product['image'] ? $product['image']['src'] : null,
                'shopify_url' => $shopify_url . $product['id'],
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
}
