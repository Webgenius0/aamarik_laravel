<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendsResource;
use App\Models\User;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class FriendshipController extends Controller
{

    /**
     * List of following friends with Leader profile by ID or if not provided then show the current user profile
     */
    public function index(Request $request)
    {
        try {
            $leaderID = $request->input('leaderID');

            // If leaderID is provided, find the user by ID
            if ($leaderID) {
                $leader = User::find($leaderID);

                // If the user doesn't exist, return a 404 response
                if (!$leader) {
                    return $this->sendResponse((object)[], 'Leader not found');
                }
            } else {
                $leader = auth()->user();
            }

            // Get the list of friends
            $friends = $leader->following;

            return $this->sendResponse(FriendsResource::collection($friends),$friends->isEmpty() ? 'No Following friends found' : 'Following Friends fetched successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error fetching friends', $th->getMessage());
        }
    }


    /**
     * List of follower friends with Leader profile by ID or if not provided then show the current user profile
     */
     public function follower(Request $request)
     {
         try {
             $leaderID = $request->input('leaderID');

             // If leaderID is provided, find the user by ID
             if ($leaderID) {
                 $leader = User::find($leaderID);

                 // If the user doesn't exist, return a 404 response
                 if (!$leader) {
                     return $this->sendResponse((object)[], 'Leader not found');
                 }
             } else {
                 $leader = auth()->user();
             }

             // Get the list of friends
             $friends = $leader->followers;

             return $this->sendResponse(FriendsResource::collection($friends),$friends->isEmpty() ? 'No Follower friends found' : 'Follower Friends fetched successfully');
         } catch (\Throwable $th) {
             return $this->sendError('Error fetching friends', $th->getMessage());
         }
     }

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
