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
            'is_wishlist'  => $this->is_wishlist($this->id),
        ];
    }


    /**
     * Check if the location is in the wishlist
     */
    private function is_wishlist($locationID)
    {
        //get currect auth user
        $user = auth()->user();
        $is_wishlist = $user->wishlists->where('location_id', $locationID)->first();
        return $is_wishlist ? 'yes' : 'no';
    }
}
