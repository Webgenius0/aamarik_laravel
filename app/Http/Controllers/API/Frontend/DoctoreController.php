<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctoresResource;
use App\Models\User;
use App\Traits\apiresponse;
use Illuminate\Http\Request;

class DoctoreController extends Controller
{
    use apiresponse;
    /**
     * list of doctores data for home page
     */
    public function index()
    {
        $doctores = User::where('role','doctor')->get();
        if ($doctores->isEmpty()) {
            return $this->sendResponse((object)[], 'Doctors List not found');
        }
        return  $this->sendResponse(DoctoresResource::collection($doctores), 'Doctors List retrieved');
    }
}