<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TreatMent;
use App\Models\TreatMentCategory;
use App\Models\TreatmentDetails;
use App\Models\TreatMentFaq;
use App\Models\DetailsItems;
use App\Models\TreatMentMedicines;
use App\Models\Medicine;
use App\Models\Assessment;

use App\Models\AboutTreatment;

class TreatMentController extends Controller
{
    public function index()
    {
        return view('backend.layouts.treatment.index');
    }

    public function store(Request $request)
    {   dd($request->all());
        // Validate the request data
        $request->validate([
 
            // Treatment - main fields
            'treatments.*.name' => 'required|string|max:255',
            'treatments.*.avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048', // Avatar is optional but should be an image
    
            // Categories - related to treatment
            'treatments.*.categories' => 'array',  // Categories should be an array
            'treatments.*.categories.*.icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048', // Icon is optional but should be an image
            'treatments.*.categories.*.title' => 'required|string|max:255',
    
            // Details - related to treatment
            'treatments.*.details' => 'array',  // Details should be an array
            'treatments.*.details.*.title' => 'required|string|max:255',
            'treatments.*.details.*.avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048', // Avatar is optional but should be an image
    
            // Detail Items - related to treatment
            'treatments.*.detail_items' => 'array',  // Detail items should be an array
            'treatments.*.detail_items.*.title' => 'required|string|max:255',
    
            // About section
            'treatments.*.about.title' => 'required|string|max:255',
            'treatments.*.about.description' => 'nullable|string|max:1000',
    
            // FAQs - related to treatment
            'treatments.*.faqs' => 'array',  // FAQs should be an array
            'treatments.*.faqs.*.question' => 'required|string|max:255',
            'treatments.*.faqs.*.answer' => 'required|string|max:1000',
    
            // Assessments - related to treatment
            'treatments.*.assessments' => 'array',  // Assessments should be an array
            'treatments.*.assessments.*.question' => 'required|string|max:255',
            'treatments.*.assessments.*.options' => 'required|array|min:2|max:4', // Assessments should have 2 to 4 options
            'treatments.*.assessments.*.options.*' => 'required|string|max:255',
            'treatments.*.assessments.*.answer' => 'required|string|max:255',
            'treatments.*.assessments.*.note' => 'nullable|string|max:1000', // Optional note
        ]);
    
        // Check if $request->treatments is set and not null
        if (!isset($request->treatments) || empty($request->treatments)) {
            return response()->json(['message' => 'No treatment data found.'], 400);
        }
    
        // Assuming $request->treatments is a single treatment, not an array
        $treatmentData = $request->treatments;
    
        // Create the treatment
        $treatment = Treatment::create([
            'name' => $treatmentData['name'],
            'avatar' => $treatmentData['avatar'],
        ]);
    
        // Categories
        foreach ($treatmentData['categories'] as $category) {
            TreatmentCategory::create([
                'treatment_id' => $treatment->id,
                'icon' => $category['icon'],
                'title' => $category['title'],
            ]);
        }
    
        // Details
        foreach ($treatmentData['details'] as $detail) {
            TreatmentDetails::create([
                'treatment_id' => $treatment->id,
                'title' => $detail['title'],
                'avatar' => $detail['avatar'],
            ]);
        }
    
        // Detail Items
        foreach ($treatmentData['detail_items'] as $item) {
            DetailsItems::create([
                'treatment_id' => $treatment->id,
                'title' => $item['title'],
            ]);
        }
    
        // About
        AboutTreatment::create(array_merge($treatmentData['about'], ['treatment_id' => $treatment->id]));
    
        // FAQs
        foreach ($treatmentData['faqs'] as $faq) {
            TreatmentFaq::create(array_merge($faq, ['treatment_id' => $treatment->id]));
        }
    
        // Medicines - Random active medicines
        $medicines = Medicine::where('status', 'active')->inRandomOrder()->take(4)->get();
        foreach ($medicines as $medicine) {
            TreatmentMedicines::create([
                'treatment_id' => $treatment->id,
                'medicine_id' => $medicine->id,
            ]);
        }
    
        // Assessments
        foreach ($treatmentData['assessments'] as $assessment) {
            Assessment::create(array_merge($assessment, ['treatment_id' => $treatment->id]));
        }
    
        // Return a success response
        return response()->json(['message' => 'Treatment created successfully!'], 200);
    }
}    