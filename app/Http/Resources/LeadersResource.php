<?php

namespace App\Http\Resources;

use App\Models\LocationReach;
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
            'id'        => $this->id,
            'name'      => $this->name,
            'avatar'    => $this->avatar,
            'points'    => $this->points,
            'level'     => $this->levelCalculation($this->id),
            'rank'      => $this->rankCalculation(),
            'is_follow' => $this->isFollowing($this->id),
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


    /**
     * calculate level of the leader based on complete group puzzle
     */
    private function levelCalculation($leaderID)
    {
        // Initialize level to 0 (no level)
        $level = 0;

        // Retrieve all group IDs for the leader
        $groupsID = LocationReach::where('user_id', $leaderID)
            ->groupBy('group_id')
            ->get(['group_id']);

        // Iterate through each group
        foreach ($groupsID as $groupID) {
            // Count how many LocationReach entries exist for the current group_id
            $groupPuzzles = LocationReach::where('user_id', $leaderID)
                ->where('group_id', $groupID->group_id)
                ->count();

            // If the user has completed exactly 9 puzzles in the group, increase level
            if ($groupPuzzles == 9) {
                $level++;
            }
        }
        return $level; // Return the calculated level
    }

    /**
     * check if the current user is following the leader or check it's own profile
     */
    private function isFollowing($leaderID)
    {
        // Retrieve the current user
        $currentUser = auth()->user();

        // If the current user is the leader, return 'self'
        if ($currentUser->id == $leaderID) {
            return 'self';
        } else {
            // Check if the user is already following the friend
            $isFollowing = $currentUser->following()->where('followed_id', $leaderID)->exists();
            return $isFollowing ? 'yes' : 'no';
        }
    }
}
