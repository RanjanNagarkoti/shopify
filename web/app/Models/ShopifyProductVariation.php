<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopifyProductVariation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'product_id',
        'session_id',
        'shopify_product_id',
        'shopify_variation_id',
        'title',
        'price',
        'sku',
        'inventory_policy',
        'variation_options',
        'image_id',
        'inventory_item_id',
        'inventory_quantity',
    ];


    /**
     * Relationship between ShopifyProductVariation and ShopifyProduct
     * 
     * @return BelongsTo
     */
    public function shopifyProduct(): BelongsTo
    {
        return $this->belongsTo(ShopifyProduct::class, 'product_id');
    }
}
