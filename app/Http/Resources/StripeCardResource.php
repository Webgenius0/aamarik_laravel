<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StripeCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        return [

            'id'        => $this['id'] ?? null,
            'brand'     => $this['card']['brand'] ?? null,
            'last4'     => $this['card']['last4'] ?? null,
            'exp_month' => $this['card']['exp_month'] ?? null,
            'exp_year'  => $this['card']['exp_year'] ?? null,
            'default'   => $this->default ?? false,
            'billing_details' => [
                'name'  => $this['billing_details']['name'] ?? null,
                'email' => $this['billing_details']['email'] ?? null,
            ],
        ];
    }
}
