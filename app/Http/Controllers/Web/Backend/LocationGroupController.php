<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Models\Location;
use App\Models\LocationGroup;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class LocationGroupController extends Controller
{



    /**
     * List of location groups
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Fetch the location groups with their associated locations
            $locationGroups = LocationGroup::with(['location'])->latest()->get();

            return DataTables::of($locationGroups)
                ->addIndexColumn()
                ->addColumn('location_name', function ($data) {
                    return Str::limit($data->location->title, 50, '...');
                })
                ->addColumn('group_name', function ($data) {
                    return Str::limit($data->name, 50, '...');
                })
                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                       <a href="javascript:void(0);" class="btn bg-success text-white rounded edit-location-group-btn" data-id="' . $data->id . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['location_name', 'group_name', 'action'])
                ->make(true);
        }

        // Get active locations for the location group creation form
        $locations = Location::where('status', 'active')->get();

        // Return the view with the locations
        return view('backend.layouts.location-group.index', compact('locations'));
    }

    /**
     * Location group data store in database
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'location_id'  => 'required|integer|exists:locations,id',
            'name'         => 'required|string|max:255',
            'images'       => 'required|array|size:9',
            'images.*'     => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Images must be valid
        ], [
            'location_id.required' => 'Location is required',
            'location_id.integer'  => 'Location must be an integer',
            'location_id.exists'   => 'Location does not exist',
            'name.required'        => 'Name is required',
            'name.string'          => 'Name must be a string',
            'name.max'             => 'Name must not be greater than 255 characters',
            'images.required'      => 'Images are required',
            'images.*.image'       => 'Images must be valid images',
            'images.*.mimes'       => 'Images must be in a valid format',
            'images.*.max'         => 'Images must not be greater than 2048 kiloby',
        ]);

        //check if location exists on location group table
        $location = LocationGroup::where('location_id', $validated['location_id'])->first();
        if ($location) {
            return response()->json(['message' => 'Location already exists in location group'], 422);
        }


        // Log uploaded files for debugging
        if ($request->hasFile('images')) {
            $uploadedImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $rand = Str::random(10);
                    $uploadedImages[] = Helper::fileUpload($file, 'group', $rand);
                }
            }
        }

        //store data in database
        LocationGroup::create([
            'location_id' => $validated['location_id'],
            'name'        => $validated['name'],
            'images'      => $uploadedImages,
        ]);

        flash()->addSuccess('Location Group created successfully');

        return redirect()->route('group.index');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function show($id)
    {
        // Fetch the location group and associated location data
        $locationGroup = LocationGroup::with('location')->findOrFail($id);

        // Fetch all active locations
        $locations = Location::where('status', 'active')->get();


        // Ensure image URLs are fully qualified
        $locationGroup->images = array_map(function ($image) {
            return asset($image);
        }, $locationGroup->images);

        // Return the data as JSON for the modal
        return response()->json([
            'locationGroup' => $locationGroup,
            'locations' => $locations
        ]);
    }


    /**
     * Location group data update in database
     */
    public function update(Request $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'location_id'  => 'required|integer|exists:locations,id',
            'name'         => 'required|string|max:255',
            'images'       => 'nullable|array|size:9',
            'images.*'     => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Images must be valid
        ], [
            'location_id.required' => 'Location is required',
            'location_id.integer'  => 'Location must be an integer',
            'location_id.exists'   => 'Location does not exist',
            'name.required'        => 'Name is required',
            'name.string'          => 'Name must be a string',
            'name.max'             => 'Name must not be greater than 255 characters',
            'images.nullable'      => 'No new images provided',
            'images.*.image'       => 'Images must be valid images',
            'images.*.mimes'       => 'Images must be in a valid format',
            'images.*.max'         => 'Images must not be greater than 2048 kilobytes',
        ]);

        // Fetch the location group record to update
        $locationGroup = LocationGroup::findOrFail($id);

        // Check if the location already exists in another group
        $existingLocationGroup = LocationGroup::where('location_id', $validated['location_id'])->where('id', '!=', $id)->first();
        if ($existingLocationGroup) {
            return response()->json(['message' => 'Location already exists in another location group'], 422);
        }

        // Handle file uploads if there are new images
        $uploadedImages = $locationGroup->images; // Default to existing images
        if ($request->hasFile('images')) {
            // If new images are uploaded, replace the old ones
            $uploadedImages = [];
            foreach ($request->file('images') as $file) {
                $rand = Str::random(10);
                $uploadedImages[] = Helper::fileUpload($file, 'group', $rand);
            }
        }

        // Update the location group data in the database
        $locationGroup->update([
            'location_id' => $validated['location_id'],
            'name'        => $validated['name'],
            'images'      => $uploadedImages, // Update the images field
        ]);

        // Flash success message
        flash()->addSuccess('Location Group updated successfully');

        // Redirect back to the location groups index page
        return redirect()->route('group.index');
    }
}
