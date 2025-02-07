<?php

namespace App\Http\Controllers\Web\Backend\user;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerManagementController extends Controller
{
    /**
     * Display list of Customer
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Group by type
            $data = User::where('role','user')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('phone', function ($data) {
                    return $data->phone ? $data->phone : '--- --- ---';
                })
                ->addColumn('gender', function ($data) {
                    switch ($data->gender) {
                        case 'male':
                            return 'Male';
                        case 'female':
                            return 'Female';
                        case 'other':
                            return 'Other';
                        default:
                            return 'N/A';
                    }
                })
                ->addColumn('total_order', function ($data) {
                    return Order::where('user_id',$data->id)->count();
                })

                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                            <a href="' . route('customer.order.sheet', ['id' => $data->id]) . '" class="btn bg-info text-white rounded" title="Show Order Details">
                                <i class="fa-solid fa-note-sticky"></i>
                            </a>
                            <a href="javascript:void(0);" onclick="view(' . $data->id . ')" class="btn bg-info text-white rounded" title="View">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                          <!---  <a href="' . route('customer.order.sheet', ['id' => $data->id]) . '" class="btn bg-danger text-white rounded" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a> --->
                    </div>';
                })
                ->rawColumns(['total_order','gender','action'])
                ->make(true);
        }
        return view('backend.layouts.customer.index');
    }


    /**
     * Show customer Details
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'gender' => $user->gender,
                'address' => $user->address,
                'avatar' => $user->avatar,
            ],
        ]);

    }


    /**
     * Customer order sheet
     */
    public function orderSheet(Request $request,$id)
    {
        // Fetch the customer
        $customer = User::findOrFail($id);
//
//        // Fetch all orders related to this customer
//        $orders = Order::where('user_id', $id)->get();
//
//        return view('backend.layouts.customer.order_sheet', compact('customer', 'orders'));

        if ($request->ajax()) {
            // Group by type
            $data = Order::where('user_id', $id)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('order_code', function ($data) {
                    return '#' . $data->uuid;
                })
                ->addColumn('sub_total', function ($data) {
                    return $data->sub_total;
                })
                ->addColumn('discount', function ($data) {
                    return $data->discount;
                })
                ->addColumn('total', function ($data) {
                    return $data->total_price;
                })
                ->addColumn('pay_amount', function ($data) {
                    return $data->pay_amount;
                })
                ->addColumn('order_date', function ($data) {
                     return $data->created_at->format('d-m-Y');
                })
                ->addColumn('delivery_date', function ($data) {
                    return $data->status == 'delivered' ? $data->updated_at->format('d-m-Y') : 'Not Delivered';
                })
                ->addColumn('status', function ($data) {
                    return $data->status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                            <a href="javascript:void(0);" onclick="viewOrderDetails(' . $data->id . ')" class="btn bg-info text-white rounded">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                    </div>';
                })
                ->rawColumns(['order_code','order_date','order_status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.customer.order_sheet',compact('customer'));
    }

}
