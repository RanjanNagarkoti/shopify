<?php

namespace App\Http\Resources;

use App\Http\Resources\ShopifyProductVariantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopifyProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shopify_product_id' => $this->shopify_product_id,
            'title' => $this->title,
            'handle' => $this->handle,
            'description' => $this->description,
            'vendor' => $this->vendor,
            'product_type' => $this->product_type,
            'tags' => explode(',', $this->tags),
            'status' => $this->status,
            'image_src' => $this->image_src,
            'shopify_url' => $this->shopify_url,
            'variants' => ShopifyProductVariantResource::collection($this->shopifyProductVariations),
        ];
    }
}
