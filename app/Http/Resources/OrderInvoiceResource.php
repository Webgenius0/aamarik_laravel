<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderInvoiceResource extends JsonResource
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
            'status' => $this->status,
            'order_date' => $this->created_at->format('Y-m-d'),
            'delivery_date' => $this->delivery_date ?? 'Not Assigned',
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
                'gp_number' => $this->billingAddress->gp_number ?? '',
                'gp_address' => $this->billingAddress->gp_address ?? '',
            ],
            'order_items' => $this->orderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->medicine->title,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ];
            }),
            'treatment' => [
                'id' => optional($this->treatment)->id,
                'name' => optional($this->treatment)->name,
            ],
            'sub_total' => $this->sub_total,
            'discount' => $this->discount,
            'shipping_charge' => $this->shipping_charge,
            'tax' => $this->tax,
            'total_price' =>   number_format($this->sub_total - $this->discount + $this->royal_mail_tracked_price + $this->shipping_charge + $this->tax, 2, '.', ''),
            'note' => $this->note ?? null,
        ];
    }
}
