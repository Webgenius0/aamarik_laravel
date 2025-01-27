<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class userSubscriptionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        //find order with stripe -> metadata -> order_id and user_id
        $order_uuid    = $this->metadata->order_id;
        $order_user_id = $this->metadata->user_id;

        // Find the order using the extracted metadata
        $order = null;
        if ($order_uuid && $order_user_id) {
            $order = Order::where('uuid', $order_uuid)
                ->where('user_id', $order_user_id)
                ->first();
        }

        return [
            'id'                   => $this->id,
            'status'               => $this->status,
            'current_period_end'   => date('Y-m-d H:i:s', $this->current_period_end),
            'current_period_start' => date('Y-m-d H:i:s', $this->current_period_start),
            'product'              => $this->plan->product,
            'metadata' => [
                'order_id'   => $order_uuid,
                'user_id'    => $order_user_id,
                'order_data' => $order ? $order->created_at->format('Y-m-d') : null, // Check if order exists
            ],
            'plan' => [
                'id'       => $this->plan->id,
                'amount'   => $this->plan->amount / 100, // Convert from cents to dollars
                'interval' => $this->plan->interval,
                'currency' => $this->plan->currency,
            ],
            'payment_method' => [
                'card_brand' => $this->card['brand'] ?? null,
                'card_last4' => $this->card['last4'] ?? null,
            ],
        ];
    }
}
