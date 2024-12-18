<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupShowResource;
use App\Http\Resources\GroupsResource;
use App\Models\LocationGroup;
use Illuminate\Http\Request;

class LocationGroupContoller extends Controller
{
    /**
     * List all location groups
     */
    public function index()
    {
        try {
            $locationGroups = LocationGroup::latest()->get();

            return $this->sendResponse(GroupsResource::collection($locationGroups), 'Location groups retrieved successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Failed to retrieve location groups', $th->getMessage());
        }
    }

    /**
     * show location group by id
     */
    public function show($groupID)
    {
        try {
            $locationGroup = LocationGroup::find($groupID);

            if (!$locationGroup) {
                return $this->sendResponse((object)[], 'Location group not found');
            }
            return $this->sendResponse(new GroupShowResource($locationGroup), 'Location groups show successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Failed to show location group', $th->getMessage());
        }
    }
}
