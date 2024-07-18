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
}
