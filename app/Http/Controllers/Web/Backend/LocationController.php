<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helper\Helper;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\LocationRequest;
use App\Models\User;
use App\Notifications\SendChallengeNotifyUser;
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
                ->addColumn('image', function ($data) {
                    return "<img src='" . asset($data->image) . "' width='100' height='50' />";
                })
                ->addColumn('puzzle_image', function ($data) {
                    return "<img src='" . asset($data->puzzle_image) . "' width='100' height='50' />";
                })
                ->addColumn('status', function ($data) {
                    $status = 'N/A';
                    $status = '<input class="form-switch text-info" type="checkbox" onclick="ShowStatusChangeAlert(' . $data->id . ')" role="switch" id="flexSwitchCheckChecked" ' . ($data->status == "active" ? 'checked' : '') . '>';
                    return $status;
                })

                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1   ">
                        <a href="' . route('location.edit', $data->id) . '" class=" btn bg-success text-white rounded">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class=" btn bg-danger text-white rounded" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['title', 'image', 'puzzle_image', 'status', 'action'])
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
    public function store(LocationRequest $request)
    {
        try {
            $validated = $request->only(['title', 'address', 'latitude', 'longitude', 'subtitle', 'image', 'information', 'map_image', 'map_url', 'points', 'puzzle_image']);


            //check if already exists or not latutude and longitude
            $location = Location::where('latitude', $request->latitude)->where('longitude', $request->longitude)->first();
            if ($location) {
                flash()->error('Location already exists');
                return redirect()->back()->withInput();
            }

            if ($request->hasFile('image')) {
                $rand = Str::random(10);
                $url = Helper::fileUpload($request->file('image'), 'location', $rand);
                $validated['image'] = $url;
            }

            if ($request->hasFile('map_image')) {
                $rand = Str::random(10);
                $url = Helper::fileUpload($request->file('map_image'), 'location', $rand);
                $validated['map_image'] = $url;
            }

            if ($request->hasFile('puzzle_image')) {
                $rand = Str::random(10);
                $url = Helper::fileUpload($request->file('puzzle_image'), 'location', $rand);
                $validated['puzzle_image'] = $url;
            }

            $location = Location::create($validated);

            //send notification to all users
            $users = User::where('role', 'user')->get();
            foreach ($users as $user) {
                $data = [
                    'title'   =>  $location->title,
                    'message' =>  $location->address,
                ];
                $user->notify(new SendChallengeNotifyUser($data, $user));
            }


            flash()->addSuccess('Location created successfully');

            return redirect()->route('location.index');
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
        $location = Location::where('id', $id)->first();
        if (empty($location)) {
            flash()->error('Location not found');
            return redirect()->route('location.index');
        }
        return view('backend.layouts.location.update', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View | \Illuminate\Http\RedirectResponse
     */
    public function update($id, LocationRequest $request)
    {
        try {
            $location = Location::where('id', $id)->first();
            if (empty($location)) {
                flash()->error('Location not found');
                return redirect()->route('location.index');
            }
            $validated = $request->only(['title', 'address', 'latitude', 'longitude', 'subtitle', 'information', 'map_url', 'points']);

            if ($request->hasFile('image')) {
                $rand = Str::random(10);
                $url = Helper::fileUpload($request->file('image'), 'location', $rand);
                $validated['image'] = $url;
            }

            if ($request->hasFile('map_image')) {
                $rand = Str::random(10);
                $url = Helper::fileUpload($request->file('map_image'), 'location', $rand);
                $validated['map_image'] = $url;
            }

            if ($request->hasFile('puzzle_image')) {
                $rand = Str::random(10);
                $url = Helper::fileUpload($request->file('puzzle_image'), 'location', $rand);
                $validated['puzzle_image'] = $url;
            }

            Location::where('id', $id)->update($validated);

            flash()->success('Location Updated successfully');

            return redirect()->route('location.index');
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
        $data = Location::where('id', $id)->first();
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
        $data = Location::where('id', $id)->first();

        if ($data->image || File::exists(public_path($data->image))) {
            File::delete(public_path($data->image));
        }

        if ($data->map_image || File::exists(public_path($data->map_image))) {
            File::delete(public_path($data->map_image));
        }

        if ($data->puzzle_image || File::exists(public_path($data->puzzle_image))) {
            File::delete(public_path($data->puzzle_image));
        }

        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Location deleted successfully.',
        ]);
    }
}
