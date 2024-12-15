<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Models\Location;
use App\Models\LocationGroup;
use App\Models\LocationGroupImage;
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
            'groupLocation'   => 'required|integer|exists:locations,id',
            'group_name'      => 'required|string|max:255',
            'slotImages'      => 'required|array|size:9',
            'slotImages.*'    => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Images must be valid
            'slotLocation'    => 'required|array|size:9',
            'slotLocation.*'  => 'integer|exists:locations,id',
        ], [
            'groupLocation.required' => 'Location is required',
            'groupLocation.integer'  => 'Location must be an integer',
            'groupLocation.exists'   => 'Location does not exist',
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
        $location = LocationGroup::where('location_id', $validated['groupLocation'])->first();
        if ($location) {
            flash()->addError('Location already exists in location group');
        }

        // Create a new location group
        $locationGroup = LocationGroup::create([
            'location_id' => $validated['groupLocation'],
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
    // public function show($id)
    // {
    //     // Fetch the location group and associated location data
    //     $locationGroup = LocationGroup::with('location','images')->findOrFail($id);

    //     // Fetch all active locations
    //     $locations = Location::where('status', 'active')->get();


    //     $locationGroupImages = $locationGroup->images;




    //     // Ensure image URLs are fully qualified
    //     $locationGroup->images = array_map(function ($image) {
    //         return asset($image);
    //     }, $locationGroup->images);




    //     // Return the data as JSON for the modal
    //     // return response()->json([
    //     //     'locationGroup' => $locationGroup,
    //     //     'locations' => $locations
    //     // ]);
    // }

    public function show($id)
    {
        // Fetch the location group and associated location data
        $locationGroup = LocationGroup::with('location', 'images')->findOrFail($id);

        // Fetch all active locations
        $locations = Location::where('status', 'active')->get();

        // Handle images: Ensure they are an array and map asset URLs correctly
        if ($locationGroup->images) {
            $locationGroup->images = $locationGroup->images->map(function ($image) {
                return asset($image->avatar);
            });
        } else {
            $locationGroup->images = []; // Default to empty array if no images
        }
        return response()->json([
            'locationGroup' => $locationGroup,
            'locations' => $locations,
            'images' => $locationGroup->images,
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




    //     public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'location_id' => 'required|integer|exists:locations,id',
    //         'images' => 'required|array',
    //         'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'puzzel_location_id' => 'required|array',
    //         'puzzel_location_id.*' => 'integer|exists:locations,id',
    //     ]);

    //     // Store the group
    //     $group = new Group();
    //     $group->name = $request->name;
    //     $group->location_id = $request->location_id;
    //     $group->save();

    //     // Store images and their locations
    //     foreach ($request->images as $index => $image) {
    //         $imagePath = $image->store('group_images', 'public');
    //         $group->images()->create([
    //             'image_path' => $imagePath,
    //             'location_id' => $request->puzzel_location_id[$index],
    //         ]);
    //     }

    //     return redirect()->route('group.index');
    // }

}
