<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class  SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View | \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SocialMedia::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                /*   ->addColumn('content', function ($data) {
                    return Str::limit($data->content, 50, '......');
                }) */
                ->addColumn('title', function ($data) {
                    return '<div class="inline-flex items-center px-3 py-1 rounded-full gap-x-2 text-emerald-500 bg-emerald-100/60">
                                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M10 3L4.5 8.5L2 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>

                                                        <h2 class="text-sm font-normal">' . $data->title . '</h2>
                                                    </div>';
                })
                ->addColumn('status', function ($data) {
                    $status = 'N/A';

                    $status = '<input class="form-switch text-info" type="checkbox" onclick="ShowStatusChangeAlert(' . $data->id . ')" role="switch" id="flexSwitchCheckChecked" ' . ($data->status == "1" ? 'checked' : '') . '>';

                    return $status;
                })

                ->addColumn('action', function ($data) {

                    return '<div class="inline-flex gap-1   ">
                    <button onclick="editMedia(' . $data->id . ')" class=" btn bg-success text-white rounded">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                      
                        <a href="' . route('social.media.destroy', $data->id) . '" onclick="showDeleteConfirm(' . $data->id . ')" class=" btn bg-danger text-white rounded" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.social-media.index');
    }

    /**
     * Create a new resource Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    /*  public function create()
    {
        return view('backend.layouts.social-media.create');
    } */

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View | \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|unique:social_media,title',
            'url' => 'required|url',
            'id' => 'nullable|exists:social_media,id',
        ]);

        try {
            $data = SocialMedia::updateOrCreate(
                ['id' => $request->id],
                [
                    'title' => $request->title,
                    'url' => $request->url,
                ]
            );

            flash()->success('Social Media created successfully');

            return response()->json([
                'success' => true,
                'message' => 'Social Media created successfully.',
            ]);
        } catch (\Exception $exception) {
            flash()->error($exception->getMessage());
            return redirect()->back()->withInput();
        }
    }



    /**
     * Show Edit Page
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $socialMedia = SocialMedia::where('id', $id)->first();
        if (empty($socialMedia)) {
            flash()->error('Social Media not found');
        }
        return response()->json([
            'success' => true,
            'data' => $socialMedia,
        ]);
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View | \Illuminate\Http\RedirectResponse
     */

    public function status($id)
    {
        $data = SocialMedia::where('id', $id)->first();
        if ($data->status == 1) {
            $data->status = 0;
            $data->save();
            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
            ]);
        } else {
            $data->status = 1;
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
        $data = SocialMedia::where('id', $id)->first();

        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Social Media deleted successfully.',
        ]);
    }
}
