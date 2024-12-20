<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendsResource extends JsonResource
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
            'name' => $this->name,
            'avatar' => $this->avatar,
            'is_following' => $this->is_following($this->id),
        ];
    }

    /**
     * Check if the user is following the friend
     */
    private function is_following($friendID)
    {
        // Get the authenticated user
        $currentUser = auth()->user();

        // Check if the user is already following the friend
        $isFollowing = $currentUser->following()->where('followed_id', $friendID)->exists();
        return $isFollowing ? 'yes' : 'no';
    }
}
