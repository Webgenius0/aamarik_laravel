<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

}
