<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthReviewResource extends JsonResource
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
            'order_uuid' => $this->uuid,
            'review' => $this->review ? [
                'rating'     => $this->review->rating, // Corrected typo: 'reting' to 'rating'
                'review'     => $this->review->review,
                'created_at' => $this->review->created_at,
            ] : null,
        ];
    }
}
