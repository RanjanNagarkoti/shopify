<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscribtionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'charge_name' => $this->chargeName,
            'amount' => $this->amount,
            'currency_code' => $this->currencyCode,
            'interval' => $this->interval
        ];
    }
}
