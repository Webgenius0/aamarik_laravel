<?php

namespace App\Http\Controllers\Web\Backend;


use App\Models\DuaCategory;
use Illuminate\Http\Request;
use App\Models\DuaSubcategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DuaCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View | \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $categories = DuaCategory::all();

        if ($request->ajax()) {

            $data = DuaCategory::with('subcategories')->get(); // Updated relationship

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return '<div class="inline-flex items-center px-3 py-1 rounded-full gap-x-2 text-emerald-500 bg-emerald-100/60">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 3L4.5 8.5L2 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <h2 class="text-sm font-normal">' . $data->title . '</h2>
                            </div>';
                })
                ->addColumn('subcategory', function ($data) {
                    $subcategories = $data->subcategories;
                    $subcat_html = '';

                    foreach ($subcategories as $subcategory) {
                        $subcat_html .= '<span class="inline-flex items-center px-3 py-1 bg-emerald-100/60 text-sm font-medium rounded-full">
                                            ' . $subcategory->title . '
                                            <button type="button" class="ml-2 text-red-500 delete-subcategory" data-id="' . $subcategory->id . '">
                                                &times;
                                            </button>
                                         </span>&nbsp;';
                    }

                    return $subcat_html;
                })

                ->addColumn('status', function ($data) {
                    return '<input class="form-switch text-info" type="checkbox" onclick="ShowStatusChangeAlert(' . $data->id . ')" role="switch" id="flexSwitchCheckChecked" ' . ($data->status == "active" ? 'checked' : '') . '>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                                <button onclick="editDuaCategory(' . $data->id . ')" class=" btn bg-success text-white rounded">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class=" btn bg-danger text-white rounded" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['title', 'subcategory', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.dua-category.index', compact('categories'));
    }

    // Fetch all categories for dropdown subcategory
    public function fetchCategories()
    {
        $categories = DuaCategory::all();
        return response()->json($categories);
    }



    //    Store a newly created resource in storage.
    public function store(Request $request)
    {
        // Define custom validation messages
        $messages = [
            'title.required' => 'The category title is required.',
            'title.string'   => 'The category title must be a string.',
            'title.unique'   => 'Category already exists.',
            'id.exists'      => 'The selected category does not exist.',
        ];

        // Create a validator instance
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:dua_categories,title',
            'id' => 'nullable|exists:dua_categories,id',
        ], $messages);

        // Validate the request
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422); // Unprocessable Entity status code
        }

        try {
            // Create the category
            $data = DuaCategory::updateOrCreate(
                ['id' => $request->id],
                [
                    'title' => $request->title,
                ]
            );

            flash()->success('Dua Category created successfully');

            return response()->json([
                'success' => true,
                'message' => 'Dua Category created successfully.',
            ]);
        } catch (\Exception $exception) {
            // Check if the exception is due to a unique constraint violation
            if ($exception->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'errors' => ['title' => ['Category already exists.']],
                ], 409); // Conflict status code
            }

            flash()->error('An error occurred: ' . $exception->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }


    public function Sub_category(Request $request)
    {
        // Validation
        $messages = [
            'sctitle.required' => 'The subcategory title is required.',
            'sctitle.string'   => 'The subcategory title must be a string.',
            'sctitle.unique'   => 'Subcategory already exists.', // Custom error message
            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category does not exist.',
        ];

        $validated = $request->validate([
            'sctitle' => 'required|string|max:255|unique:dua_subcategories,title',
            'category_id' => 'required|exists:dua_categories,id',
        ], $messages);

        try {
            // Create new subcategory
            DuaSubcategory::create([
                'title' => $validated['sctitle'],
                'category_id' => $validated['category_id']
            ]);

            // Return success message in JSON response
            flash()->success('Subcategory created successfully !!');
            return response()->json([
                'success' => true,
                'message' => 'Subcategory created successfully.',
            ]);
        } catch (\Exception $exception) {
            flash()->error($exception->getMessage());
            return response()->json([
                'success' => false,
                'errors' => ['message' => $exception->getMessage()]
            ], 500);
        }
    }

    //delete subcategory function
    public function deleteSubcategory(Request $request)
    {
        $subcategory = DuaSubcategory::find($request->id);

        if ($subcategory) {
            $subcategory->delete();
            return response()->json([
                'success' => true,
                'message' => 'Subcategory deleted successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Subcategory not found.'
            ]);
        }
    }



    //edit category
    public function edit($id)
    {
        $DuaCategory = DuaCategory::where('id', $id)->first();
        if (empty($DuaCategory)) {
            flash()->error('DuaCategory not found');
        }
        return response()->json([
            'success' => true,
            'data' => $DuaCategory,
        ]);
    }


    /**
     * Change Status the specified resource from storage.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function status($id)
    {
        $data = DuaCategory::where('id', $id)->first();
        if ($data->status == 'active') {
            $data->status = 'inactive';
            $data->save();
            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
            ]);
        } else {
            $data->status = 'active';
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
            ]);
        }
    }

    /**
     * Delete selected item
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $data = DuaCategory::where('id', $id)->first();

        $data->delete();
        return response()->json([
            'success' => false,
            'message' => 'DuaCategory deleted successfully.',
        ]);
    }
}
