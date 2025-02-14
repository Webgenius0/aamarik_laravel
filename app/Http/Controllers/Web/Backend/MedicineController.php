<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\MedicineFeature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\MedicineDetails;
use App\Helper\Helper;
use App\Models\MedicineImages;
Use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;


class MedicineController extends Controller
{
    public function index(Request $request){

        if ($request->ajax()) {
            // Group by type
            $data = Medicine::with('details')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Title', function ($data) {
                    return Str::limit($data->title, 50, '...');
                })
                ->addColumn('brand', function ($data) {
                    return Str::limit($data->brand, 50, '...');
                })
                ->addColumn('quantity', function ($data) {
                    return $data->details->quantity ?? 0;  // Use a default value if null
                })
                ->addColumn('stock_quantity', function ($data) {
                    return Str::limit($data->details->stock_quantity?? 0);
                })
                ->addColumn('expiry_date', function ($data) {
                    if ($data->details->expiry_date) {
                        // Convert expiry_date to Carbon instance
                        $expiryDate = Carbon::parse($data->details->expiry_date);

                        // Check if the expiry date is close
                        $expiryDateClass = (now() == $expiryDate || now() > $expiryDate->subDay(5)) ? 'bg-red-500 text-white' : 'bg-white';

                        // Return the formatted expiry date
                        return "<span class=\"$expiryDateClass\">" . $data->details->expiry_date . "</span>";
                    } else {
                        return "<span class=\"\">N/A</span>";
                    }
                })
                ->addColumn('status', function ($data) {
                    return '<input type="checkbox" class="form-switch" onclick="ShowStatusChangeAlert(' . $data->id . ')" ' . ($data->status == "active" ? 'checked' : '') . '>';
                })


                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                        <a href="javascript:void(0);" onclick="editMedicine(' . $data->id . ')" class="btn bg-success text-white rounded">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['title','brand','quantity','expiry_date','stock_quantity','status', 'action','avatar'])
                ->make(true);
        }
        return view('backend.layouts.medicine.create-medicine');
    }

    public function Store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'avatar' => 'nullable|array',
            'avatar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,webp,bmp,svg', // Array of images
            'form' => 'nullable|in:tablet,liquid,capsule,inhaler,syrup,ointment',
            'dosage' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'buying_price' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|integer',
            'stock_quantity' => 'nullable|integer',
            'expiry_date' => 'nullable|date',
            'feature' => 'nullable|array',
            'feature.*' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Create the medicine entry
            $medicine = Medicine::create([
                'title' => $request->input('title'),
                'brand' => $request->input('brand'),
                'generic_name' => $request->input('generic_name'),
                'description' => $request->input('description'),
                'status' => 'active', // Adjust the status if needed
            ]);

            // Initialize an array to store avatar paths
            $avatarPaths = [];

            // Check if 'avatar' files are provided and store them in 'medicine_images' table
            if ($request->hasFile('avatar')) {
                foreach ($request->file('avatar') as $file) {
                    // Store each image and save it in 'medicine_images' table
                    $uniqueName = uniqid() . '-' . $file->getClientOriginalName();
                    $path = Helper::fileUpload($file, 'medicine', $uniqueName);

                    MedicineImages::create([
                        'medicine_id' => $medicine->id,  // Link the image to the medicine ID
                        'image' => $path,  // Store the image path
                    ]);
                }
            }

            // Create the medicine detail entry without avatar since we're saving images in the medicine_images table
            $medicineDetail = MedicineDetails::create([
                'medicine_id' => $medicine->id,
                'form' => $request->input('form'),
                'dosage' => $request->input('dosage'),
                'unit' => $request->input('unit'),
                'price' => $request->input('price'),
                'buying_price' => $request->input('buying_price'),
                'expiry_date' => $request->input('expiry_date'),
                'quantity' => $request->input('quantity'),
                'stock_quantity' => $request->input('stock_quantity'),
            ]);

            // Store features if provided
            if ($request->has('feature')) {
                foreach ($request->input('feature') as $feature) {
                    MedicineFeature::create([
                        'medicine_id' => $medicine->id,
                        'feature' => $feature,
                    ]);
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Medicine created successfully!']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Medicine creation failed!']);
        }
    }

    //edit medicine
    public function edit($id)
    {
        $medicine = Medicine::with('details')->find($id);
        $features = MedicineFeature::where('medicine_id', $id)->pluck('feature')->toArray();
        $images=MedicineImages::where('medicine_id',$id)->pluck('image')->toArray();

        if ($medicine) {
            return response()->json(['success' => true, 'data'=>$medicine,'features'=>$features,'images'=>$images]); // Make sure the FAQ object is returned properly
        }



        return response()->json(['success' => false, 'message' => 'Medicine not found']);
    }
    //Update Medicine

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:active,inactive',
            'form' => 'nullable|string|max:255',
            'dosage' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'price' => 'nullable|numeric',
            'buying_price' => 'nullable|numeric',
            'expiry_date' => 'nullable|date',
            'quantity' => 'nullable|integer',
            'stock_quantity' => 'nullable|integer',
            'avatar' => 'nullable|array',
            'avatar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'feature' => 'nullable|array',
            'feature.*' => 'nullable|string|max:255',
        ]);


        DB::beginTransaction();

        try {
            // Find the medicine by ID, including related details
            $medicine = Medicine::find($id);

            if (!$medicine) {
                return response()->json(['success' => false, 'message' => 'Medicine not found'], 404);
            }

            // Update the medicine entry
            $medicine->update([
                'title' => $request->input('title'),
                'brand' => $request->input('brand'),
                'generic_name' => $request->input('generic_name'),
                'description' => $request->input('description'),
                'status' => $request->input('status', 'active'), // Default to 'active' if not provided
            ]);

            // Handle the avatar upload (if present)
            $avatarPaths = [];
            if ($request->hasFile('avatar')) {
                // If an avatar already exists, unlink (delete) the old files
                foreach ($medicine->images as $existingImage) {
                    $oldImagePath = storage_path('app/public/' . $existingImage->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath); // Delete the old avatar file
                    }
                }

                // Upload the new avatar files and store their paths
                foreach ($request->file('avatar') as $file) {
                    $uniqueName = uniqid() . '-' . $file->getClientOriginalName();
                    $path = Helper::fileUpload($file, 'medicine', $uniqueName);

                    // Store the image in the database
                    MedicineImages::create([
                        'medicine_id' => $medicine->id, // Link the image to the medicine ID
                        'image' => $path, // Store the image path
                    ]);
                }
            }

            // Update or create the medicine details
            $medicineDetail = $medicine->details;
            if ($medicineDetail) {
                $medicineDetail->update([
                    'form' => $request->input('form'),
                    'dosage' => $request->input('dosage'),
                    'unit' => $request->input('unit'),
                    'price' => $request->input('price'),
                    'buying_price' => $request->input('buying_price') ?? $medicineDetail->buying_price,
                    'expiry_date' => $request->input('expiry_date') ?? $medicineDetail->expiry_date,
                    'quantity' => $request->input('quantity'),
                    'stock_quantity' => $request->input('stock_quantity'),
                ]);
            } else {
                MedicineDetails::create([
                    'medicine_id' => $medicine->id,
                    'form' => $request->input('form'),
                    'dosage' => $request->input('dosage'),
                    'unit' => $request->input('unit'),
                    'price' => $request->input('price'),
                    'buying_price' => $request->input('buying_price'),
                    'expiry_date' => $request->input('expiry_date'),
                    'quantity' => $request->input('quantity'),
                    'stock_quantity' => $request->input('stock_quantity'),
                ]);
            }

            // Update the medicine features (if provided)
            if ($request->has('feature')) {

                // First, delete the existing features
                MedicineFeature::where('medicine_id', $medicine->id)->delete();

                // Add the new features
                foreach ($request->input('feature') as $feature) {
                    MedicineFeature::create([
                        'medicine_id' => $medicine->id,
                        'feature' => $feature,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Medicine updated successfully!']);
        } catch (Exception $e) {
            // Rollback the transaction if anything goes wrong
            DB::rollBack();
            Log::error('Error updating medicine: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Medicine update failed!'.$e->getMessage()]);
        }
    }


    //status update
    public function updateStatus($id)
    {
        $medicine = Medicine::find($id);
        if (!$medicine) {
            return response()->json(['success' => false, 'message' => 'Not found']);
        }
        $medicine->status = $medicine->status == 'active' ? 'inactive' : 'active';
        $medicine->save();
        return response()->json(['success' => true, 'message' => 'FAQ Status update successfully']);
    }

    //delete medicine
    public function destroy($id)
    {
        $medicine = Medicine::find($id);
        if (!$medicine) {
            return response()->json(['success' => false, 'message' => 'Medicine Not found']);
        }
        $medicine->delete();

        return response()->json(['success' => true, 'message' => 'Medicine deleted successfully']);
    }
}




















