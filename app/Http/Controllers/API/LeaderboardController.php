<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendsResource;
use App\Http\Resources\LeadersResource;
use App\Models\LocationReach;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    /**
     * Get Leaderboard
     */
    public function index()
    {
        try {
            // Fetch users sorted by points in descending order
            $leaders = User::where('role', '!=', 'admin')->orderByDesc('points')->get();

            // Return the response
            return $this->sendResponse(LeadersResource::collection($leaders), $leaders->isEmpty() ? 'No leaders found' : 'Leaders fetched successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error fetching leaderboard', $th->getMessage());
        }
    }


    /**
     * Show leader profile by ID or the current user profile.
     */
    public function show(Request $request)
    {
        $leaderID = $request->input('leaderID');
        $all  = $request->input('all') ? 'yes' : 'no';

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

        try {
            // Get the leader's reach puzzles
            $locationGroupImages = $this->getLeaderReachPuzzles($leader->id, $all);

            // Return the custom response
            $response = $this->customResponse($locationGroupImages, $leader);

            return $this->sendResponse($response, $leaderID ? 'leader profile fetched successfully' : 'user profile fetched successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error fetching leader profile', $th->getMessage());
        }
    }



    /**
     * get leader reach puzzles
     */
    private function getLeaderReachPuzzles($leaderID, $all)
    {
        $puzzleReach  = LocationReach::with(['group', 'user', 'image'])->where('user_id', $leaderID)
            ->latest()
            ->when($all == 'no', function ($query) {
                return $query->take(20);
            })
            ->get();

        if (empty($puzzleReach)) {
            return  $this->sendResponse([], 'No location group images found');
        }

        //custom response
        $response = $puzzleReach->map(function ($reach) {
            return [
                'id'     => $reach->image->location->id ?? null,
                'name'   => $reach->group->name ?? null,
                'points' => $reach->image->location->points ?? null,
                'avatar' => $reach->image->location->puzzle_image ?? null,
            ];
        });
        return $response;
    }


    /**
     * Get leders reach puzzles group count
     */
    private function getLeaderReachPuzzlesGroup($leaderID)
    {
        $leader = User::find($leaderID);

        // Get unique group IDs
        $uniqueGroupCount = LocationReach::where('user_id', $leaderID)
            ->distinct('group_id')  // Ensure distinct groups
            ->count('group_id');

        return $uniqueGroupCount;
    }

    /**
     * Get the first puzzle reach date
     */
    private function getStartDate($leaderID)
    {
        // Get the first puzzle reach date
        $startDate = LocationReach::where('user_id', $leaderID)
            ->oldest('created_at')
            ->value('created_at');

        return $startDate;
    }

    /**
     * Get points of the leader
     */
    private function getLeaderPoints($leaderID)
    {
        // Get the leader
        $leader = User::find($leaderID);

        // Get the total points of users
        $totalPointsWithoutLeader = User::where('role', '!=', 'admin')
            ->sum('points');

        $leaderPoint = $leader->points . '/' . $totalPointsWithoutLeader;

        return $leaderPoint;
    }

    /**
     * Custom response for the leader or user profile
     */
    private function customResponse($locationGroupImages, $leader)
    {
        $response = [
            'profile'   => new LeadersResource($leader),
            'points'    => $this->getLeaderPoints($leader->id),
            'puzzle_stops_visited' => $this->getLeaderReachPuzzles($leader->id, 'yes')->count(),
            'puzzle_board' => $this->getLeaderReachPuzzlesGroup($leader->id),
            'start_date' => $this->getStartDate($leader->id),
            'followers' => $leader->followers->count(),
            'following' => $leader->following->count(),
            'puzzles'   => $locationGroupImages,
        ];
        return $response;
    }
}