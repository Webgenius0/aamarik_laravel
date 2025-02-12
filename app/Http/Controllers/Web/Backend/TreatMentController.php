<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTreatmentRequest;
use App\Http\Requests\UpdateTreatmentRequest;
use App\Models\AboutTreatment;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\DetailsItems;
use App\Models\Medicine;
use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\TreatmentDetails;
use App\Models\TreatmentFaq;
use App\Models\TreatmentMedicines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helper\Helper;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
class TreatMentController extends Controller
{
    public function index()
    {
        //get medicines
        $medicines = Medicine::with('details')->where('status','active')->get();
        return view('backend.layouts.treatment.create',compact('medicines'));
    }
//
    public function treatmentList(Request $request)
    {
        if ($request->ajax()) {
            $data = Treatment::latest()->get();


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('avatar', function ($data) {
                    $avatarUrl = $data->avatar ? asset($data->avatar) : asset('uploads/defult-image/default-avatar.png');
                    return $data->avatar ?
                        '<img src="' . $avatarUrl . '" alt="Avatar" width="50" height="50">' :
                        'No Avatar';
                })

                ->addColumn('action', function ($data) {
                    $editUrl = route('treatment.edit', $data->id);

                    return '<div class="inline-flex gap-1">
                            <a href="' . $editUrl . '" class="btn bg-success text-white rounded">
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

        return view('backend.layouts.treatment.index');
    }
    public function store(StoreTreatmentRequest $request)
    {

        // Start a database transaction
        DB::beginTransaction();
        try {
            // Collect the treatment data
            $treatmentData = $request->validated();

            // Handle the avatar upload for Treatment
            $avatarPath = null;
            if (isset($treatmentData['avatar']) && $treatmentData['avatar']) {
                $avatarPath = Helper::fileUpload($treatmentData['avatar'], 'treatments', 'avatar');
            }

            // Create Treatment
            $treatment = Treatment::create([
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

                    TreatmentCategory::create([
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
            if (isset( $treatmentData['about']) && is_array($treatmentData['about'])) {
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

            // Attach medicines for treatment
            foreach ($treatmentData['medicines'] as $medicine) {
                TreatmentMedicines::create([
                    'treatment_id' => $treatment->id,
                    'medicine_id' => $medicine,
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

            DB::commit();
            // Return success message
            return response()->json([
                'success' => true,
                'message' => 'Treatment created successfully!',
                'redirect' => route('treatment.list') // Redirect URL
            ]);

        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to create Treatment!']);
        }

    }

    /**
     * Edit treatment and related data
     */
    public function edit($treatmentId)
    {
        // Retrieve the treatment with all related data
        $data = Treatment::with([
            'categories',
            'detail',
            'detailItems',
            'about',
            'faqs',
            'assessments',
            'medicines'
        ])->find($treatmentId);


        // Check if the treatment was found
        if (!$data) {
            return redirect()->route('treatment.list')->with('error', 'Treatment not found!');
        }

        // Fetch all active medicines
        $medicines = Medicine::where('status', 'active')->get();

        return view('backend.layouts.treatment.edit', compact('data', 'medicines'));
    }


    /**
     * Update treatment and related data
     */
    public function update(UpdateTreatmentRequest $request, $treatmentId)
    {
        DB::beginTransaction();
        try {
            $id = $treatmentId;
            // Find the treatment record
            $treatment = Treatment::findOrFail($id);

            // Handle avatar update
            if ($request->hasFile('avatar')) {
                //delete old image
                if($treatment->avatar && file_exists(public_path($treatment->avatar))) {
                    unlink(public_path($treatment->avatar));
                }
                $avatarPath = Helper::fileUpload($request->file('avatar'), 'treatments', 'avatar');
                $treatment->avatar = $avatarPath;
            }


            // Update Treatment Data
            $treatment->name = $request->input('name');
            $treatment->save();

            //handel update old data and add new category
            $this->handelUpdateAndAddNewCategory($request, $treatment);

            //   Update or Create Treatment Details
            if ($request->has('details')) {
                foreach ($request->details as $detailData) {
                    // Check if ID exists to update, otherwise create a new one
                    $detail = TreatmentDetails::updateOrCreate(
                        [
                            'id' => $detailData['id'] ?? null, // Update if ID exists
                            'treatment_id' => $treatment->id,
                        ],
                        [
                            'title' => $detailData['title'],
                            'avatar' => isset($detailData['avatar'])
                                ? Helper::fileUpload($detailData['avatar'], 'details', 'detail_avatar')
                                : (isset($detailData['id']) ? TreatmentDetails::find($detailData['id'])->avatar ?? null : null),
                        ]
                    );

                    // Handle avatar removal if a new one is uploaded
                    if (isset($detailData['avatar']) && $detailData['avatar']) {
                        $oldDetail = TreatmentDetails::find($detailData['id']);
                        if ($oldDetail && file_exists(public_path($oldDetail->avatar))) {
                            unlink(public_path($oldDetail->avatar));
                        }
                        $detail->avatar = Helper::fileUpload($detailData['avatar'], 'details', 'detail_avatar');
                        $detail->save();
                    }
                }
            }


            //   **Update Detail Items**
            if ($request->has('detail_items')) {
                foreach ($request->detail_items as $itemData) {
                    DetailsItems::updateOrCreate(
                        ['treatment_id' => $treatment->id, 'title' => $itemData['title']],
                        ['title' => $itemData['title']]
                    );
                }
            }


            //   Update About Section
            if ($request->has('about')) {
                foreach ($request->about as $aboutData) {
                    // Find existing AboutTreatment record (if exists)
                    $about = AboutTreatment::where('treatment_id', $treatment->id)->first();

                    // Handle avatar update and delete old file
                    if (isset($aboutData['avatar']) && $aboutData['avatar']) {
                        if ($about && file_exists(public_path($about->avatar))) {
                            unlink(public_path($about->avatar)); //   Delete old avatar
                        }
                        $avatarPath = Helper::fileUpload($aboutData['avatar'], 'about_treatments', 'avatar');
                    } else {
                        $avatarPath = $about->avatar ?? null; // Keep old avatar if no new one is uploaded
                    }

                    //   Update or Create AboutTreatment
                    AboutTreatment::updateOrCreate(
                        ['treatment_id' => $treatment->id], // Find by treatment_id
                        [
                            'title' => $aboutData['title'],
                            'avatar' => $avatarPath,
                            'short_description' => $aboutData['short_description'],
                        ]
                    );
                }
            }



            //   **Update FAQs**
            if ($request->has('faqs')) {
                foreach ($request->faqs as $faqData) {
                    TreatmentFaq::updateOrCreate(
                        ['treatment_id' => $treatment->id, 'question' => $faqData['question']],
                        ['answer' => $faqData['answer']]
                    );
                }
            }

            //   **Update Medicines**
            if ($request->has('medicines')) {
                $treatment->medicines()->sync($request->medicines);
            }

            //   **Update Assessments**
            if ($request->has('assessments')) {
                foreach ($request->assessments as $assessmentData) {
                    Assessment::updateOrCreate(
                        ['treatment_id' => $treatment->id, 'question' => $assessmentData['question']],
                        [
                            'option1' => $assessmentData['option1'],
                            'option2' => $assessmentData['option2'],
                            'option3' => $assessmentData['option3'] ?? null,
                            'option4' => $assessmentData['option4'] ?? null,
                            'answer' => $assessmentData['answer'] ?? null,
                            'note' => $assessmentData['note'] ?? null,
                        ]
                    );
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Treatment updated successfully!',
                'redirect' => route('treatment.list'),
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update Treatment!', 'error' => $exception->getMessage()]);
        }
    }


    /**
     * Handel update and add new category
     */
    private function handelUpdateAndAddNewCategory($request, $treatment): void
    {
        //  Update Categories
        if ($request->has('categories')) {

            $existingCategoryIds = $treatment->categories->pluck('id')->toArray();

            $incomingCategoryIds = collect($request->categories)
                ->filter(fn($category) => !empty($category['id']))
                ->pluck('id')
                ->toArray();


            //  Identify and Delete Removed Categories & Their Images
            $categoriesToDelete = array_diff($existingCategoryIds, $incomingCategoryIds);

            $categories = TreatmentCategory::whereIn('id', $categoriesToDelete)->get();

            foreach ($categories as $category) {
                //  Delete category image if it exists
                if ($category->icon && file_exists(public_path($category->icon))) {
                    unlink(public_path($category->icon));
                }

                //   Now delete the category
                $category->delete();
            }

            foreach ($request->categories as $categoryData) {
                if (!empty($categoryData['id'])) {
                    $category = TreatmentCategory::find($categoryData['id']);
                    dd($category);
                } else {
                    $category = new TreatmentCategory();
                    $category->treatment_id = $treatment->id;
                }

                if (isset($categoryData['icon']) && $categoryData['icon']) {
                    //delete old image
                    if ($category->icon && file_exists(public_path($category->icon))) {
                        unlink(public_path($category->icon));
                    }
                    $category->icon = Helper::fileUpload($categoryData['icon'], 'categories', 'icon');
                }
                $category->title = $categoryData['title'];
                $category->save();
            }
        }
    }


    /**
     * delete treatment and related data
     */
    public function destroy($id)
    {
        $treatment = Treatment::with([
            'categories',
            'detail',
            'detailItems',
            'about',
            'faqs',
            'assessments',
            'medicines',
        ])->find($id);

        if (!$treatment) {
            return response()->json(['success' => false, 'message' => 'Treatment not found']);
        }

        DB::beginTransaction();
        try {
            // Delete categories and associated icons
            $categories = TreatmentCategory::where('treatment_id', $treatment->id)->get();
            foreach ($categories as $category) {
                if (!empty($category->icon) && file_exists(public_path($category->icon))) {
                    unlink(public_path($category->icon));
                }
                $category->delete();
            }

            // Delete treatment details and avatars
            $details = TreatmentDetails::where('treatment_id', $treatment->id)->get();
            foreach ($details as $detail) {
                if (!empty($detail->avatar) && file_exists(public_path($detail->avatar))) {
                    unlink(public_path($detail->avatar));
                }
                $detail->delete();
            }

            // Delete details items
            DetailsItems::where('treatment_id', $treatment->id)->delete();

            // Delete about treatment and avatars
            $aboutItems = AboutTreatment::where('treatment_id', $treatment->id)->get();
            foreach ($aboutItems as $about) {
                if (!empty($about->avatar) && file_exists(public_path($about->avatar))) {
                    unlink(public_path($about->avatar));
                }
                $about->delete();
            }

            // Delete FAQs
            TreatmentFaq::where('treatment_id', $treatment->id)->delete();

            // Delete assessments and assessment results
            $assessments = Assessment::where('treatment_id', $treatment->id)->get();
            foreach ($assessments as $assessment) {
                AssessmentResult::where('treatment_id', $treatment->id)->delete();
                $assessment->delete();
            }

            // Delete treatment medicines
            TreatmentMedicines::where('treatment_id', $treatment->id)->delete();

            // Delete treatment avatar if exists
            if (!empty($treatment->avatar) && file_exists(public_path($treatment->avatar))) {
                unlink(public_path($treatment->avatar));
            }

            // Finally, delete the treatment record
            $treatment->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Treatment deleted successfully!'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete treatment. Please try again.'
            ]);
        }
    }


}
