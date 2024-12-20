<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadersResource extends JsonResource
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
            'id'     => $this->id,
            'name'   => $this->name,
            'avatar' => $this->avatar,
            'points' => $this->points,
            'level'  => '1',
            'rank'   => $this->rankCalculation(),
        ];
    }

    /**
     * leader rank in the leaderboard
     */
    public function rankCalculation()
    {
        // Retrieve all non-admin users
        $leaders = User::where('role', '!=', 'admin')
            ->orderByDesc('points')
            ->pluck('id'); // Get only the IDs for efficient ranking

        // Find the index of the current user ID in the sorted list
        $user_rank = $leaders->search($this->id);

        // Return rank as a 1-based index
        return $user_rank !== false ? $user_rank + 1 : null;
    }
}
