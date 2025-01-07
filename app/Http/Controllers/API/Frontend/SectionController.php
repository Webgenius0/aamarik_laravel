<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use App\Traits\apiresponse;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    use apiresponse;
    /**
     * Get Healthcare Section data with type
     */
    public function getSection(Request $request)
    {
        try {
            $type = $request->input('type');
            if (!$type || !in_array($type, ['healthcare', 'process'])) {
                return $this->sendError(' Invalid type', []);
            }

            return $this->getSectionData($type);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error Retrieving Section Data', $th->getMessage());
        }
    }


    //get section data
    private function getSectionData($type)
    {
        $data = Section::where('type', $type)->with('sectionCards')->first();

        if (!$data) {
            return $this->sendResponse((object)[], 'No data found');
        }
        // Determine the section type label (Healthcare or Working Process)
        $sectionTypeLabel = $type == 'healthcare' ? 'Healthcare' : 'Working Process';

        return $this->sendResponse(new SectionResource($data), "{$sectionTypeLabel} Section Data Retrieved Successfully");
    }
}
