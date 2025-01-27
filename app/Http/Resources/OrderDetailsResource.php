<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
           'order_status' => $this->status,
            'note' => $this->note,
            'prescription' => $this->prescription,
           'details' => [
               'order_id' => $this->uuid,
               'name' => $this->user->name,
               'email' => $this->user->email,
               'date_of_birth' => $this->user->date_of_birth,
                'phone' => $this->billingAddress->contact
           ] ,
          'order_items' => $this->orderItems->map(function ($item) {
              return [
                  'medicine' => $item->medicine->title,
                  'quantity' => $item->quantity,
                  'unit_price' => $item->unit_price,
                  'total_price' => $item->total_price,
              ];
          }),
          'orders' =>[
              'amount' => $this->sub_total,
              'discount' => $this->discount,
              'total' => $this->total_price
          ]
        ];
    }
}
