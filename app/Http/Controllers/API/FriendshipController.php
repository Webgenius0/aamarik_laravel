<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    /**
     * Follow and unfollow a user
     */
    public function friendship($friendID)
    {
        try {
            // Get the authenticated user
            $currentUser = auth()->user();

            // Check if you cannot follow yourself
            if ($currentUser->id == $friendID) {
                return $this->sendError('You cannot following yourself.', [], 400);
            }

            // Check if the friend ID is valid
            $friend = User::find($friendID);
            if (!$friend) {
                return $this->sendResponse((object)[], 'This user not found');
            }

            // Check if the user is already following the friend
            $isFollowing = $currentUser->following()->where('followed_id', $friendID)->exists();

            // If the user is following the friend, unfollow them
            if ($isFollowing) {
                $currentUser->following()->detach($friendID);
                return $this->sendResponse([], 'Unfollowed successfully');
            }

            // If the user is not following the friend, follow them
            $currentUser->following()->attach($friendID);

            return $this->sendResponse([], 'Followed successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error following user', $th->getMessage());
        }
    }
}
