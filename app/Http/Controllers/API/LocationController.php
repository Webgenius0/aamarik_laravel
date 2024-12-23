<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $locations = Location::where('status', 'active')->latest()->get();
            $response  = $locations->map(function ($location) {
                return [
                    'id'          => $location->id,
                    'title'       => $location->title,
                    'address'     => $location->address,
                    'latitude'    => $location->latitude,
                    'longitude'   => $location->longitude,
                    'puzzle_image'=> $location->puzzle_image,
                ];
            });
            return $this->sendResponse($response, $locations ? 'Locations retrieved successfully.' : 'No locations found.');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError(' Fail to retrieve locations.', $th->getMessage());
        }
    }
}
