<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PuzzelReachResource;
use App\Models\LocationGroup;
use App\Models\LocationGroupImage;
use App\Models\LocationReach;
use Illuminate\Http\Request;

class LocationReachContoller extends Controller
{
    /**
     * set location reach
     */
    public function set_location_reach($groupID, $puzzelID)
    {
        try {
            //check if groupID and puzzelID are valid
            if ($groupID == null || $puzzelID == null) {
                return $this->sendError('Invalid groupID or puzzelID', 400);
            }

            //find the location group with the given ID
            $locationGroup = LocationGroup::find($groupID);
            if (!$locationGroup) {
                return $this->sendResponse((object)[], 'Location group not found');
            }


            //find the puzzel with the given ID
            $puzzel = LocationGroupImage::find($puzzelID);
            if (!$puzzel) {
                return $this->sendResponse((object)[], 'Puzzel not found');
            }


            //check alrady reach this puzzel
            $locationReach = LocationReach::where('group_id', $groupID)->where('image_id', $puzzelID)->first();
            if ($locationReach) {
                return $this->sendResponse([], 'Already reach this puzzel');
            }

            //set the location reach
            $locationReach = LocationReach::create([
                'user_id'  => auth()->user()->id,
                'group_id' => $groupID,
                'image_id' => $puzzelID,
            ]);

            return $this->sendResponse(new PuzzelReachResource($locationReach), 'Location reach set successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error set location reach', $th->getMessage());
        }
    }
}
