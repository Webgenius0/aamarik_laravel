<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Models\Location;
use App\Models\LocationGroup;
use App\Models\LocationGroupImage;
use App\Models\LocationReach;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
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
            $locationGroups = LocationGroup::latest()->get();

            return DataTables::of($locationGroups)
                ->addIndexColumn()
                ->addColumn('group_name', function ($data) {
                    return Str::limit($data->name, 50, '...');
                })
                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                       <a href="' . route('group.edit', $data->id) . '"  class="btn bg-success text-white rounded edit-location-group-btn">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['group_name', 'action'])
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
            'group_name'      => 'required|string|max:255|unique:location_groups,name',
            'slotImages'      => 'required|array|size:9',
            'slotImages.*'    => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Images must be valid
            'slotLocation'    => 'required|array|size:9',
            'slotLocation.*'  => 'integer|exists:locations,id',
        ], [
            'group_name.required'    => 'Group Name is required',
            'group_name.string'      => 'Group Name must be a string',
            'group_name.max'         => 'Group Name must not be greater than 255 characters',
            'slotImages.required'    => 'Group images are required',
            'slotImages.size'        => 'Group images must be 9',
            'slotImages.*.image'     => 'Group images must be an image',
            'slotImages.*.mimes'     => 'Group images must be a valid image type',
            'slotImages.*.max'       => 'Group images must not be greater than 2048',
            'slotLocation.required'  => 'Group location is required',
            'slotLocation.array'     => 'Group location must be an array',
            'slotLocation.size'      => 'Group location must be 9',
            'slotLocation.exists'    => 'Group Location does not exist'
        ]);

        //check if location exists on location group table
        $location = LocationGroup::where('name', $validated['group_name'])->first();
        if ($location) {
            flash()->addError('Location already exists in location group');
        }

        // Create a new location group
        $locationGroup = LocationGroup::create([
            'name'        => $validated['group_name'],
        ]);


        //location group images
        foreach ($validated['slotImages'] as $key => $image) {

            // Ensure location_id is provided before inserting
            if (!isset($validated['slotLocation'][$key])) {
                flash()->addError('Location for image slot is missing');
                return redirect()->back();
            }
            //image upload
            $imagePath = '';
            if ($image) {
                $rand = Str::random(10);
                $imagePath = Helper::fileUpload($image, 'group', $rand);
            }

            //store data in database
            LocationGroupImage::create([
                'location_group_id'  => $locationGroup->id,
                'location_id'        => $validated['slotLocation'][$key],
                'avatar'             => $imagePath,
            ]);
        }

        flash()->addSuccess('Location Group created successfully');
        return redirect()->route('group.index');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Fetch the location group and associated location data
        $locationGroup = LocationGroup::with('images')->findOrFail($id);

        // Fetch all active locations
        $locations = Location::where('status', 'active')->get();

        // Return the view with the locations
        return view('backend.layouts.location-group.edit', compact('locationGroup', 'locations'));
    }

    // Update the Location Group
    public function update(Request $request, $id)
    {
        // Validation rules
        $request->validate([
            'name'      => 'required|string|max:255',
            'locations' => 'required|array',
            'images'    => 'nullable|array',
            'images.*'  => 'nullable|image|max:2048',
        ]);

        // Find the Location Group by ID
        $locationGroup = LocationGroup::find($id);

        if (!$locationGroup) {
            // If Location Group not found, add error and redirect
            flash()->addError('Location Group not found');
            return redirect()->route('group.index');
        }

        // Update the Location Group name
        $locationGroup->update([
            'name' => $request->input('name')
        ]);

        // Process the images and locations
        if ($request->has('locations') && count($request->input('locations')) > 0) {
            foreach ($request->locations as $key => $location) {
                // Find the corresponding LocationGroupImage by the key
                $locationGroupImage = LocationGroupImage::find($key);

                if ($locationGroupImage) {
                    // Get the current avatar path (old image)
                    $imagePath = $locationGroupImage->avatar;

                    // Get the new uploaded image if available
                    $newImage = $request->images[$key] ?? null;

                    // If a new image is provided, process it
                    if ($newImage && $newImage->isValid()) {
                        // Delete the old image file if it exists
                        if ($imagePath && File::exists(public_path($imagePath))) {
                            File::delete(public_path($imagePath));
                        }

                        // Generate a random name and upload the new image
                        $rand = Str::random(10);
                        $imagePath = Helper::fileUpload($newImage, 'group', $rand);
                    }

                    // Update the LocationGroupImage record
                    $locationGroupImage->update([
                        'location_id' => $location,  // Update the location ID
                        'avatar'      => $imagePath,  // Update the avatar path (image path)
                    ]);
                }
            }
        }

        flash()->addSuccess('Location Group updated successfully!');
        // Redirect back with a success message
        return redirect()->route('group.index');
    }


    /**
     * destroy  the form for editing the specified resource.
     */
    public function destroy($id)
    {
        // Load the LocationGroup with its associated 'reachs' and 'images' relationships
        $locationGroup = LocationGroup::with(['reachs', 'images'])->find($id);

        if (!$locationGroup) {
            // If Location Group not found, add error and redirect
            flash()->addError('Location Group not found');
            return redirect()->route('group.index');

            return response()->json([
                'success' => false,
                'message' => 'Location Group not found',
            ]);
        }

        // Delete all associated LocationReach records using the relationship method
        $locationGroup->reachs()->delete();

        // Delete the associated LocationGroupImages and their files
        foreach ($locationGroup->images as $image) {
            if ($image->avatar && File::exists(public_path($image->avatar))) {
                File::delete(public_path($image->avatar)); // Delete the image file
            }
            $image->delete(); // Delete the image record from the database
        }

        // Finally, delete the LocationGroup itself
        $locationGroup->delete();

        // Return a JSON response with success message
        return response()->json([
            'success' => true,
            'message' => 'Location Group deleted successfully!',
        ]);
    }
}
