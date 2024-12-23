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
    public function set_location_reach($locationID)
    {
        try {
            //get current user
            $user = auth()->user();

            //find the location with the location ID
            $location = Location::find($locationID);
            if (!$location) {
                return $this->sendResponse((object)[], 'Location not found');
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

            //add points to the user
            $user->points += $puzzle->location->points;
            $user->save();

            return $this->sendResponse(new PuzzelReachResource($locationReach), 'Location reach set successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error set location reach', $th->getMessage());
        }
    }
}
