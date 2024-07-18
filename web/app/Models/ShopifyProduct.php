<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyProduct extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'session_id',
        'shopify_product_id',
        'title',
        'description',
        'vendor',
        'product_type',
        'handle',
        'tags',
        'status',
        'image_src',
        'shopify_url',
    ];


    /**
     * Get the shopify product variations associated with the shopify product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopifyProductVariations(): HasMany
    {
        return $this->hasMany(ShopifyProductVariation::class, 'product_id');
    }


    /**
     * Get the total inventory quantity information for the Shopify product variations.
     *
     * @return string|null
     */
    public function getShopifyProductVariationsInventoryAttribute(): string|null
    {
        $variantCount = $this->shopifyProductVariations->count();

        if ($variantCount === 0) {
            return null;
        }

        $inventoryQuantity = $this->shopifyProductVariations->sum('inventory_quantity');

        if ($variantCount > 1) {
            return "{$inventoryQuantity} in stock for {$variantCount} variants";
        } else {
            return "{$inventoryQuantity} in stock";
        }
    }

    public function getShopifyProductVariationsSkuAttribute(): string|null
    {
        $skus = $this->shopifyProductVariations->pluck('sku')->filter()->unique()->toArray();

        if (empty($skus)) {
            return null;
        }

        return implode(', ', $skus);
    }
}
