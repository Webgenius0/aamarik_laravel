<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Support\Facades\Validator;
use App\Models\MedicineFeature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\MedicineDetails;
use App\Helper\Helper;
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
                ->rawColumns(['title','brand','quantity','stock_quantity','status', 'action','avatar'])
                ->make(true);
        }
        return view('backend.layouts.medicine.create-medicine');
    }

    public function Store(Request $request){
//dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,webp,bmp,svg',
            'form' => 'nullable|in:tablet,liquid,capsule,inhaler,syrup,ointment',
            'doges' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|integer',
            'stock_quantity' => 'nullable|integer',
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

            $avatarPath = null;
            if ($request->hasFile('avatar')) {
               
                $avatarPath = Helper::fileUpload($request->file('avatar'), 'users', 'avatar');
               
            }
            $medicineDetail = MedicineDetails::create([
                'medicine_id' => $medicine->id,
                'avatar' => $avatarPath,
                'form' => $request->input('form'),
                'dosage' => $request->input('doges'),
                'unit' => $request->input('unit'),
                'price' => $request->input('price'),
                'quantity' => $request->input('quantity'),
                'stock_quantity' => $request->input('stock_quantity'),
            ]);
            
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
            dd($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Medicine creation failed!']);
        }
    }

    //edit medicine
    public function edit($id)
    {
        $medicine = Medicine::with('details')->find($id);
       
        if ($medicine) {
            return response()->json(['success' => true, 'data'=>$medicine]); // Make sure the FAQ object is returned properly
        }



        return response()->json(['success' => false, 'message' => 'Medicine not found']);
    }
    //Updat Medicine

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        Log::info($request->all());
        $request->validate([
            'title' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:active,inactive',
            'form' => 'nullable|string|max:255',
            'doges' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|integer',
            'stock_quantity' => 'nullable|integer',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'feature' => 'nullable|array',
            'feature.*' => 'nullable|string|max:255',
        ]);
    
        // Find the medicine by ID, including the related details
        $medicine = Medicine::with('details')->find($id);
    
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
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            // If an avatar already exists, unlink (delete) the old file
            if ($medicine->details && $medicine->details->avatar) {
                // Assuming avatars are stored in the 'avatars' directory within 'storage/app/public'
                $oldAvatarPath = storage_path( $medicine->details->avatar);
                if (file_exists($oldAvatarPath)) {
                    unlink($oldAvatarPath); // Delete the old avatar file
                }
            }
    
            // Upload the new avatar file and store its path
            $avatarPath = Helper::fileUpload($request->file('avatar'), 'users', 'avatar');
        }
    
        // Update or create the medicine details
        $medicineDetail = $medicine->details;
        $medicineDetail->update([
            'avatar' => $avatarPath,
            'form' => $request->input('form'),
            'dosage' => $request->input('doges'),
            'unit' => $request->input('unit'),
            'price' => $request->input('price'),
            'quantity' => $request->input('quantity'),
            'stock_quantity' => $request->input('stock_quantity'),
        ]);
    
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
    
        // Return success response
        return response()->json(['success' => true, 'message' => 'Medicine updated successfully']);
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
