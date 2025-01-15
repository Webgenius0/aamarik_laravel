<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicinesResource;
use App\Models\Medicine;
use App\Traits\apiresponse;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    use apiresponse;
    /**
    * List of medicin - HealthCare Services->Our Test Kit
     */
    public  function  index()
    {
        try {
            // Get medicines that are active and have stock available.
//            $medicines = Medicine::with(['details' => function ($query) {
//                $query->where('stock_quantity', '>', 0);
//            }])
//                ->where('status', 'active')
//                ->latest()->limit(4)->get();
            $medicines = Medicine::all();

            return response($medicines);

            if ($medicines->isEmpty()) {
                return $this->sendResponse((object)[], 'Medicine List not found');
            }

            return $this->sendResponse(MedicinesResource::collection($medicines), 'Medicines retrieved successfully');
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage(),[], $exception->getCode());
        }
    }
}
