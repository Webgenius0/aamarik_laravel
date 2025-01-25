<?php

namespace App\Http\Controllers\Web\Backend\coupon;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    public function index(Request $request) {
        
    
    if ($request->ajax()) {
        // Group by type
        $data = Coupon::all();

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
    {
        return view('backend.layouts.coupons.index');
    }
}

//store 
public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:255',
            'discount_type' => 'required|in:fixed,percentage', 
            'discount_amount' => 'required|numeric|min:0', 
            'usage_limit' => 'required|integer|min:1', 
            'start_date' => 'nullable|date', 
            'end_date' => 'nullable|date|after_or_equal:start_date', 
            
        ]);

        // Create a new coupon in the database
        $coupon = Coupon::create([
            'code' => $validatedData['code'],
            'discount_type' => $validatedData['discount_type'],
            'discount_amount' => $validatedData['discount_amount'],
            'usage_limit' => $validatedData['usage_limit'],
            'used_count' => 0, 
            'start_date' => $validatedData['start_date'] ?? null,
            'end_date' => $validatedData['end_date'] ?? null,
            
        ]);

        // Return a response, or you can redirect as needed
        return response()->json([
            'success' => true,
            'message' => 'Coupon created successfully!',
            'coupon' => $coupon
        ]);
    }


}