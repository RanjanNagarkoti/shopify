<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopifyProductVariantResource extends JsonResource
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
            'shopify_variation_id' => $this->shopify_variation_id,
            'title' => $this->title,
            'price' => $this->price,
            'sku' => $this->sku,
            'inventory_policy' => $this->inventory_policy,
            'variations_options' => $this->variations_options,
            'image_id' => $this->image_id,
            'inventory_item_id' => $this->inventory_item_id,
            'inventory_quantity' => $this->inventory_quantity,
        ];
    }
}
