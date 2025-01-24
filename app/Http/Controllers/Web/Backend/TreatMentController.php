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
use Illuminate\Support\Facades\Log;
use App\Helper\Helper;
use App\Models\AboutTreatment;

class TreatMentController extends Controller
{
    public function index()
    {
        return view('backend.layouts.treatment.index');
    }

    public function store(Request $request)
    {
        //dd($request->all());
       // dd($request->all('details.0.title'));
        // Validate incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',
            'categories.*.title' => 'nullable|string|max:255',
            'categories.*.icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',
            'details.*.title' => 'nullable|string|max:255',
            'details.*.avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',
            'detail_items.*.title' => 'nullable|string|max:255',
            'about.*.title' => 'nullable|string|max:255',
            'about.*.avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',
            'about.*.short_description' => 'nullable|string|max:1000',  // Add validation for description if needed
            'faqs.*.question' => 'nullable|string',
            'faqs.*.answer' => 'nullable|string',
            'assessments.*.question' => 'nullable|string',
            'assessments.*.option1' => 'nullable|string',
            'assessments.*.option2' => 'nullable|string',
            'assessments.*.option3' => 'nullable|string',
            'assessments.*.option4' => 'nullable|string',
        ]);

        // Collect the treatment data
        $treatmentData = $request->all();

        // Handle the avatar upload for Treatment
        $avatarPath = null;
        if (isset($treatmentData['avatar']) && $treatmentData['avatar']) {
            $avatarPath = Helper::fileUpload($treatmentData['avatar'], 'treatments', 'avatar');
        }
        
        // Create Treatment
        $treatment = TreatMent::create([
            'name' => $treatmentData['name'],
            'avatar' => $avatarPath,
        ]);
        
        // Process Categories (if any)
        if (isset($treatmentData['categories']) && is_array($treatmentData['categories'])) {
            foreach ($treatmentData['categories'] as $category) {
                $iconPath = null;
                if (isset($category['icon']) && $category['icon']) {
                    $iconPath = Helper::fileUpload($category['icon'], 'categories', 'icon');
                }

                TreatMentCategory::create([
                    'treatment_id' => $treatment->id,
                    'icon' => $iconPath,
                    'title' => $category['title'],
                ]);
            }
        }
        
        // Process Treatment Details (if any)
        if (isset($treatmentData['details']) && is_array($treatmentData['details'])) {
            foreach ($treatmentData['details'] as $detail) {
                $detailAvatarPath = null;
                if (isset($detail['avatar']) && $detail['avatar']) {
                    $detailAvatarPath = Helper::fileUpload($detail['avatar'], 'details', 'detail_avatar');
                }

                TreatmentDetails::create([
                    'treatment_id' => $treatment->id,
                    'title' => $detail['title'],
                    'avatar' => $detailAvatarPath,
                ]);
            }
        }
        
        // Process Detail Items (if any)
        if (isset($treatmentData['detail_items']) && is_array($treatmentData['detail_items'])) {
            foreach ($treatmentData['detail_items'] as $item) {
                DetailsItems::create([
                    'treatment_id' => $treatment->id,
                    'title' => $item['title'],
                ]);
            }
        }
        
       
     // Process About Treatment (if any)
if (isset($treatmentData['about']) && is_array($treatmentData['about'])) {
    foreach ($treatmentData['about'] as $about) {
        // Check if 'title' exists before accessing it
        if (isset($about['title'])) {
            $avatarPath = null;

            // Handle file upload for avatar
            if (isset($about['avatar']) && $about['avatar']) {
                $avatarPath = Helper::fileUpload($about['avatar'], 'about_treatments', 'avatar');
            }

            // Add description if it exists in the request
            $description = isset($about['short_description']) ? $about['short_description'] : null;

            // Create new AboutTreatment record
            AboutTreatment::create([
                'treatment_id' => $treatment->id,
                'title' => $about['title'],      // title from request
                'avatar' => $avatarPath,         // avatar path (if uploaded)
                'short_description' => $description,   // description from request
            ]);
        }
    }
}


     // Process FAQs (if any)


if (isset($treatmentData['faqs']) && is_array($treatmentData['faqs'])) {
    $warnings = []; // Array to hold the warning messages

    foreach ($treatmentData['faqs'] as $faq) {
        if (isset($faq['question']) && isset($faq['answer'])) {
            Log::info('Inserting FAQ: ', ['question' => $faq['question'], 'answer' => $faq['answer']]);

            // Proceed with storing FAQ
            TreatmentFaq::create([
                'treatment_id' => $treatment->id,
                'question' => $faq['question'],
                'answer' => $faq['answer'],
            ]);
        } else {
            // Log missing fields and store warning
            Log::warning('FAQ is missing question or answer', $faq);
            
            $warnings[] = $faq; // Collecting missing FAQ fields
        }
    }

    // Send warnings to the view, if any
   
}



        // Attach random medicines (4 active medicines)
        $medicines = Medicine::where('status', 'active')->inRandomOrder()->take(4)->get();
        foreach ($medicines as $medicine) {
            TreatmentMedicines::create([
                'treatment_id' => $treatment->id,
                'medicine_id' => $medicine->id,
            ]);
        }
        
        if (isset($treatmentData['assessments']) && is_array($treatmentData['assessments'])) {
            foreach ($treatmentData['assessments'] as $assessment) {
                // Ensure all required fields are present
                Log::info('Inserting assessment:', $assessment);
                if (isset($assessment['question']) && isset($assessment['option1']) && isset($assessment['option2'])) {
                    // Insert the assessment data
                    Assessment::create([
                        'question' => $assessment['question'],
                        'option1' => $assessment['option1'],
                        'option2' => $assessment['option2'],
                        'option3' => $assessment['option3'] ?? null,
                        'option4' => $assessment['option4'] ?? null,
                        'answer' => $assessment['answer'] ?? null,
                        'note' => $assessment['note'] ?? null,
                        'treatment_id' => $treatment->id ?? null,
                    ]);
                } else {
                    // Log missing fields
                    Log::warning('Assessment is missing required fields', $assessment);
                }
            }
        }
        

        // Return success message
        return response()->json([
            'message' => 'Treatment created successfully!',
            'data' => $treatment,
        ]);
    }
}    