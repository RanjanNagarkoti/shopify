<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyCollection extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'shopify_collection_id',
        'handle',
        'type',
        'title',
        'session_id',
        'product_count',
    ];


    /**
     * Get the shopify products associated with the shopify collection.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function shopifyProducts(): BelongsToMany
    {
        return $this->belongsToMany(
            ShopifyProduct::class,
            'collection_product',
            'collection_id',
            'product_id'
        )->withTimestamps();
    }
}
