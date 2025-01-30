<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class userOrdersResource extends JsonResource
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
            'order_date' => $this->created_at->format('Y-m-d'),
            'Delivery_date' => $this->status == 'delivered' ? $this->updated_at->format('Y-m-d') : null,
            'total' => $this->total_price,
            'status' => $this->status
        ];
    }
}
