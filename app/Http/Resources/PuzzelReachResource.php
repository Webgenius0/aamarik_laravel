<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PuzzelReachResource extends JsonResource
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
             'puzzel' => [
                'id'     => $this->image->id,
                'avatar' => $this->image->avatar,
             ],
             'location' => [
                'id'        => $this->image->location->id,
                'address'   => $this->image->location->address,
                'latitude'  => $this->image->location->latitude,
                'longitude' => $this->image->location->longitude,
             ]
        ];
    }
}
