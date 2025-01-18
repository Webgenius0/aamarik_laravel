<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\TreatmentAboutResource;
use App\Http\Resources\TreatmentAssessmentResource;
use App\Http\Resources\TreatmentDetailResource;
use App\Http\Resources\TreatmentMedicineResource;
use App\Http\Resources\TreatmentResource;
use App\Http\Resources\TreatmentServicesResource;
use App\Models\Treatment;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TreatmentController extends Controller
{
    use apiresponse;
    /**
     * List of treatments
     */
    public function index()
    {
        try {
            $treatments = Treatment::get();

            //check if treatments is empty
            if($treatments->isEmpty()){
                return $this->sendResponse([],"No treatments found");
            }
            return $this->sendResponse(TreatmentResource::collection($treatments),"Treatments Retrieved  successfully");
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage(),[],500);
        }
    }

    /**
     * List of treatment services
     */
    public function treatmentServicess($treatmentID)
    {
        try {
            //check treatment id valid or not
            $treatment = Treatment::with('categories')->find($treatmentID);

            if(!$treatment){
                return $this->sendResponse([], "Treatment not found");
            }

            return $this->sendResponse(new TreatmentServicesResource($treatment),"Treatments services Retrieved successfully");
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage(),[],500);
        }
    }

    /**
     * Show Treatment Detail -> HealthCare Service Details page
     */
    public function treatmentDetail($treatmentID)
    {
        try {
            //check treatment id valid or not
            $treatment = Treatment::with(['detail','detailItems'])->find($treatmentID);

            if(!$treatment){
                return $this->sendResponse([], "Treatment not found");
            }

            return $this->sendResponse(new TreatmentDetailResource($treatment),"Treatments services Retrieved successfully");
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage(),[],500);
        }
    }

    /**
     * Show treatment About
     */
    public function treatmentAbout($treatmentID)
    {
        try {
            //check treatment id valid or not
            $treatment = Treatment::with(['about','faqs'])->find($treatmentID);

            if(!$treatment){
                return $this->sendResponse([], "Treatment not found");
            }

            return $this->sendResponse(new TreatmentAboutResource($treatment),"Treatment about Retrieved successfully");
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage(),[],500);
        }
    }

    /**
     * list of treatment medicines
     */
    public function treatmentMedicines($treatmentID)
    {
        // Check if treatment ID is valid
        $treatment = Treatment::with([
            'medicines' => function ($query) {
                // Filter medicines where status is 'active'
                $query->where('status', 'active');
            },
            'medicines.details' => function ($query) {
                // Filter medicines where stock_quantity is greater than zero
                $query->where('stock_quantity', '>', 0);
            }
        ])->find($treatmentID);
        if(!$treatment){
            return $this->sendResponse([], "Treatment not found");
        }

        return $this->sendResponse(new TreatmentMedicineResource($treatment),"Treatment medicines Retrieved successfully");
    }

    /**
     * List of Treatment Consultation or Assessment for Assessment page
     */
    public function treatmentConsultation($treatmentID)
    {
        $treatment = Treatment::with(['assessments'])->find($treatmentID);
        if(!$treatment){
            return $this->sendResponse([], "Treatment not found");
        }
        return $this->sendResponse(new TreatmentAssessmentResource($treatment),"Treatment assessment Retrieved successfully");

    }
}
