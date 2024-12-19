<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PuzzelReachResource;
use App\Models\Location;
use App\Models\LocationGroup;
use App\Models\LocationGroupImage;
use App\Models\LocationReach;
use Illuminate\Http\Request;

class LocationReachContoller extends Controller
{
    /**
     * set location reach
     */
    public function set_location_reach(Request $request)
    {
        $latitude  = $request->input('lat');
        $longitude = $request->input('long');

        try {
            //get current user
            $user = auth()->user();

            //check if groupID and puzzelID are valid
            if ($latitude == null || $longitude == null) {
                return $this->sendError('Invalid latitude or longitude', [], 400);
            }


            //find the location
            $location = Location::where('latitude', $latitude)->where('longitude', $longitude)->first();
            if (!$location) {
                return $this->sendError('Location not found', [], 404);
            }

            //find location group image with the location ID
            $puzzle = LocationGroupImage::where('location_id', $location->id)->first();



            $puzzleReach = LocationReach::where('user_id', $user->id)->where('group_id', $puzzle->locationGroup->id)->where('image_id', $puzzle->id)->first();

            if ($puzzleReach) {
                return $this->sendResponse([], 'Already reach this puzzel');
            }
            //set the location reach
            $locationReach = LocationReach::create([
                'user_id'  => $user->id,
                'group_id' => $puzzle->locationGroup->id,
                'image_id' => $puzzle->id,
            ]);

            return $this->sendResponse(new PuzzelReachResource($locationReach), 'Location reach set successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error set location reach', $th->getMessage());
        }
    }
}
