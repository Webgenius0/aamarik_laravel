<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationShowResource extends JsonResource
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
            'id'           => $this->id,
            'title'        => $this->title,
            'address'      => $this->address,
            'subtitle'     => $this->subtitle,
            'latitude'     => $this->latitude,
            'longitude'    => $this->longitude,
            'image'        => $this->image,
            'information'  => $this->information,
            'map_image'    => $this->map_image,
            'map_url'      => $this->map_url,
            'points'       => $this->points,
            'puzzle_image' => $this->puzzle_image,
        ];
    }
}
