<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View | \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Location::latest(); 
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return Str::limit($data->title, 50, '......');
                })
                ->addColumn('status', function ($data) {
                    $status = 'N/A';

                    $status = '<input class="form-switch text-info" type="checkbox" onclick="ShowStatusChangeAlert(' . $data->id . ')" role="switch" id="flexSwitchCheckChecked" ' . ($data->status == "active" ? 'checked' : '') . '>';

                    return $status;
                })

                ->addColumn('action', function ($data) {

                    return '<div class="inline-flex gap-1   ">
                        <a href="' . route('verse.edit', $data->id) . '" class=" btn bg-success text-white rounded">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class=" btn bg-danger text-white rounded" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.location.index');
    }

    /**
     * Create a new resource Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('backend.layouts.location.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View | \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|unique:verses,content',
            'reference' => 'required|string|max:200',
        ]);

        try {
            $data = new Location();
            $data->content = $request->content;
            $data->reference = $request->reference;
            $data->save();

            flash()->addSuccess('Verse created successfully');

            return redirect()->route('verse.index');

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
        $verse = Verse::where('id', $id)->first();
        return view('backend.layouts.verse.update', compact('verse'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View | \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'content' => 'required|string|unique:verses,content,' . $request->id,
            'reference' => 'required|string|max:200',
        ]);

        try {
            $data =Verse::where('id', $request->id)->first();
            if(empty($data)){
                flash()->error('Verse not found');
                return redirect()->back()->withInput();
            }
            $data->content = $request->content;
            $data->reference = $request->reference;
            $data->save();

            flash()->success('Verse Updated successfully');

            return redirect()->route('verse.index');

        } catch (\Exception $exception) {
            flash()->error($exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Change Status the specified resource from storage.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function status($id)
    {
        $data = Verse::where('id', $id)->first();
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
        $data = Verse::where('id', $id)->first();

        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Verse deleted successfully.',
        ]);
    }
}
