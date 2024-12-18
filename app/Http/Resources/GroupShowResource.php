<?php

namespace App\Http\Resources;

use App\Models\LocationReach;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupShowResource extends JsonResource
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
            'id'   => $this->id,
            'name' => $this->name,
            'puzzles' => $this->images->map(function ($image) {
                return [
                    'id'      => $image->id,
                    'avatar'  => $image->avatar,
                    'is_reach' => $this->IsReach($image->id),
                    'location' => $image->location ? [
                        'id'        => $image->location->id ?? null,
                        'title'     => $image->location->title ?? null,
                        'address'   => $image->location->address ?? null,
                        'latitude'  => $image->location->latitude ?? null,
                        'longitude' => $image->location->longitude ?? null,
                    ] : null,
                ];
            })
        ];
    }

    /**
     * check if user has reached the puzzle
     */
    private function IsReach($puzzelID)
    {
        // Get the authenticated user ID
        $user = auth()->user()->id;

        // Check if a LocationReach record exists for the given user and puzzelID (image ID)
        $locationReachExists = LocationReach::where('user_id', $user)
            ->where('image_id', $puzzelID)
            ->exists();

        return $locationReachExists ? 'yes' : 'no';
    }
}
