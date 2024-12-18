<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishListsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'location' => $this->location ? [
                'id'           => $this->location->id ?? null,
                'title'        => $this->location->title ?? null,
                'address'      => $this->location->address ?? null,
                'points'       => $this->location->points ?? null,
                'puzzle_image' => $this->location->puzzle_image ?? null,
            ] : [],
        ];

    }
}
