<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryInfoResource;
use App\Http\Resources\HomeBannerResource;
use App\Http\Resources\PersonalizedResource;
use App\Models\CMS;
use App\Models\DeliveryInfo;
use App\Traits\apiresponse;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    use apiresponse;
    /**
     * Show home banner data with type
     */
    public function homeBanner()
    {
        try {
            // Get the CMS page based on type
            $data = CMS::where('type', 'banner')->first();

            // Check if data exists, if not, return error response
            if (!$data) {
                return $this->sendResponse((object)[], "Home Banner Data Not Found");
            }

            return $this->sendResponse(new HomeBannerResource($data), 'Home Banner Data Retrieved Successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError("Error Retrieving Home Banner Data", $th->getMessage());
        }
    }


    /**
     * Show personalized data with type
     */
    public function Personalized()
    {
        try {
            // Get the CMS page based on type
            $data = CMS::where('type', 'personalized')->first();

            // Check if data exists, if not, return error response
            if (!$data) {
                return $this->sendResponse((object)[], "Personalized Page Data Not Found");
            }

            return $this->sendResponse(new PersonalizedResource($data), 'Personalized Page Data Retrieved Successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError("Error Retrieving personalized Page Data", $th->getMessage());
        }
    }

    /**
     * Get delivery information data for Checkout page
     */
    public  function  getDeliveryInfo()
    {
        $data = DeliveryInfo::first();

        if (!$data) {
            return $this->sendResponse((object)[], "Delivery Info Data Not Found");
        }

        return $this->sendResponse(new DeliveryInfoResource($data), 'Delivery Info Data Retrieved Successfully');
    }
}
