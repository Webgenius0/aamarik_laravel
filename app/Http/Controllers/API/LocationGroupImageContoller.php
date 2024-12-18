<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationGroupImagesResource;
use App\Http\Resources\LocationShowResource;
use App\Models\Location;
use App\Models\LocationGroupImage;
use App\Models\LocationReach;
use Illuminate\Http\Request;

class LocationGroupImageContoller extends Controller
{
    /**
     * list of location group images
     * show all reach location group puzzles
     */
    public function index()
    {
        try {
            //get currect auth user
            $user = auth()->user();
            $puzzleReach  = LocationReach::with(['group', 'user', 'image'])->where('user_id', $user->id)->latest()->get();

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

            //return success response
            return  $this->sendResponse($response, 'All Puzzle retrieved successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Failed to retrieved Puzzles', $th->getMessage());
        }
    }


    /**
     * show a single puzzle details
     */
    public function show($id)
    {
        try {
            $location = Location::find($id);
            if (!$location) {
                return $this->sendResponse((object)[], 'Puzzle Details not found');
            }

            //return success response
            return $this->sendResponse(new LocationShowResource($location), 'Puzzle Details retrieved successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Failed to retrieved Puzzle Details', $th->getMessage());
        }
    }
}
