<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class userOrderDetailsResource extends JsonResource
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
            'uuid' => $this->uuid,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'billing_address' => [
                'name' => $this->billingAddress->name ?? '',
                'email' => $this->billingAddress->email ?? '',
                'address' => $this->billingAddress->address ?? '',
                'contact' => $this->billingAddress->contact ?? '',
                'city' => $this->billingAddress->city ?? '',
                'postcode' => $this->billingAddress->postcode ?? '',
            ],
            'order_items' => $this->orderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'medicine' => $item->medicine->title,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ];
            }),
            'treatment' => [
                'id' => $this->treatment->id ?? null,
                'name' => $this->treatment->name ?? null,
            ],
            'sub_total' => $this->sub_total,
            'discount' => $this->discount,
            'total_price' => $this->total_price,
            'note' => $this->note ?? null,
            'review' => $this->review ? true : false,
        ];
    }
}
