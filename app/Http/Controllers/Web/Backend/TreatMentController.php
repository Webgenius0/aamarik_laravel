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
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
class TreatMentController extends Controller
{
    public function index()
    {
        return view('backend.layouts.treatment.index');
    }
//
    public function treatmentList(Request $request)
    {
        if ($request->ajax()) {
            $data = TreatMent::latest()->get();


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('avatar', function ($data) {
                    $avatarUrl = $data->avatar ? asset($data->avatar) : asset('uploads/defult-image/default-avatar.png');
                    return $data->avatar ?
                        '<a href="' . $avatarUrl . '" target="_blank"><img src="' . $avatarUrl . '" alt="Avatar" width="50" height="50"></a>' :
                        'No Avatar';
                })

                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                            <a href="javascript:void(0);" onclick="editDoctor(' . $data->id . ')" class="btn bg-success text-white rounded">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['name', 'avatar','action','status'])
                ->make(true);

        }

        return view('backend.layouts.treatment.treatmentlist');
    }
    public function store(Request $request)
{
    // Log the entire request to see what is being submitted
    Log::info('Received Treatment Data:', ['request_data' => $request->all(), 'files' => $request->files]);

    // Validate incoming request data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',
        'categories.*.title' => 'nullable|string|max:255',
        'categories.*.icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',
        'detail_items.*.title' => 'nullable|string|max:255',
        'details.*.title' => 'nullable|string|max:255',
        'details.*.avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',
        // Add more validation rules here as needed...
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
            $avatarPath = null;
            if (isset($about['avatar']) && $about['avatar']) {
                $avatarPath = Helper::fileUpload($about['avatar'], 'about_treatments', 'avatar');
            }

            AboutTreatment::create([
                'treatment_id' => $treatment->id,
                'title' => $about['title'],
                'avatar' => $avatarPath,
                'short_description' => $about['short_description'] ?? '',
            ]);
        }
    }

    // Process FAQs (if any)
    if (isset($treatmentData['faqs']) && is_array($treatmentData['faqs'])) {
        foreach ($treatmentData['faqs'] as $faq) {
            TreatmentFaq::create([
                'treatment_id' => $treatment->id,
                'question' => $faq['question'],
                'answer' => $faq['answer'],
            ]);
        }
    }

    // Attach random medicines (4 active medicines)
    $medicines = Medicine::where('status', 'active')->inRandomOrder()->take(4)->get();
    foreach ($medicines as $medicine) {
        TreatmentMedicines::create([
            'treatment_id' => $treatment->id,
            'medicine_id' => $medicine->id,
        ]);
    }

    // Process Assessments (if any)
    if (isset($treatmentData['assessments']) && is_array($treatmentData['assessments'])) {
        foreach ($treatmentData['assessments'] as $assessment) {
            Assessment::create([
                'question' => $assessment['question'],
                'option1' => $assessment['option1'],
                'option2' => $assessment['option2'],
                'option3' => $assessment['option3'] ?? null,
                'option4' => $assessment['option4'] ?? null,
                'answer' => $assessment['answer'] ?? null,
                'note' => $assessment['note'] ?? null,
                'treatment_id' => $treatment->id,
            ]);
        }
    }

    // Return success message
    return response()->json([
        'message' => 'Treatment created successfully!',
        'data' => $treatment,
    ]);
}
}
