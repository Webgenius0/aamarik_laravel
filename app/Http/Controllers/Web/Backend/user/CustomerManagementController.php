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
    public function orderSheet($id)
    {

        // Fetch the customer
        $customer = User::findOrFail($id);

        // Fetch all orders related to this customer
        $orders = Order::where('user_id', $id)->get();

        return view('backend.layouts.customer.order_sheet', compact('customer', 'orders'));
    }

}
