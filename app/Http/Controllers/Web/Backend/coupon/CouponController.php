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
    public function index(Request $request)
    {


        if ($request->ajax()) {
            // Group by type
            $data = Coupon::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('discount', function ($data) {
                    $discount = '';
                    if ($data->discount_type == 'percentage') {
                        $discount = $data->discount_amount . '%';
                    } else {
                        $discount = $data->discount_amount;
                    }
                    return $discount;
                })
                ->addColumn('start_date', function ($data) {
                    return $data->start_date->format('d-m-Y h:i A');
                })
                ->addColumn('end_date', function ($data) {
                    return $data->end_date->format('d-m-Y h:i A');
                })
                ->addColumn('stock_quantity', function ($data) {
                    return Str::limit($data->details->stock_quantity ?? 0);
                })
                ->addColumn('status', function ($data) {
                    return '<input type="checkbox" class="form-switch" onclick="ShowStatusChangeAlert(' . $data->id . ')" ' . ($data->status == "active" ? 'checked' : '') . '>';
                })


                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                    <a href="javascript:void(0);" onclick="editCoupon(' . $data->id . ')" class="btn bg-success text-white rounded">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </div>';
                })
                ->rawColumns(['discount', 'brand', 'quantity', 'stock_quantity', 'status', 'action', 'avatar'])
                ->make(true);
        } {
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


    public function edit($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon) {
            return response()->json(['success' => true, 'data' => $coupon]); // Make sure the FAQ object is returned properly
        }

        return response()->json(['success' => false, 'message' => 'Coupon not found']);
    }

    /**
     * Update FAQ
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        $coupon->update([
            'code' => $request->code ?? $coupon->code,
            'discount_type' => $request->discount_type ?? $coupon->discount_type,
            'discount_amount' => $request->discount_amount ?? $coupon->discount_amount,
            'usage_limit' => $request->usage_limit ?? $coupon->usage_limit,
            'start_date' => $request->start_date ?? $coupon->start_date,
            'end_date' => $request->end_date ?? $coupon->end_date,
        ]);

        return response()->json(['success' => true, 'message' => 'Coupon updated successfully']);
    }


    public function updateStatus($id)
    {
        $faq = Coupon::find($id);
        if (!$faq) {
            return response()->json(['success' => false, 'message' => 'Not found']);
        }
        $faq->status = $faq->status == '1' ? '0' : '1';
        $faq->save();
        return response()->json(['success' => true, 'message' => 'Coupon Status update successfully']);
    }
}
