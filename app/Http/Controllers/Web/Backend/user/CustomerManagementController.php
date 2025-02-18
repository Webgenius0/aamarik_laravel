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

        if ($request->ajax()) {

            //get order with user id
            $data = Order::with([
                'ActivityLogs' => function ($query) {
                    $query->select('id', 'order_id', 'user_id', 'action', 'created_at');
                },
                'ActivityLogs.user' => function ($query) {
                    $query->select('name');
                },
                'orderItems.medicine' => function ($query) {
                    $query->select('id', 'title','generic_name');
                }
            ])->where('user_id', $id)->latest()->get();

//            return DataTables::of($data)
//                ->addIndexColumn()
//                ->addColumn('order_code', function ($data) {
//                    return '#' . $data->uuid;
//                })
//                ->addColumn('sub_total', function ($data) {
//                    return $data->sub_total;
//                })
//                ->addColumn('discount', function ($data) {
//                    return $data->discount;
//                })
//                ->addColumn('total', function ($data) {
//                    return $data->total_price;
//                })
//                ->addColumn('pay_amount', function ($data) {
//                    return $data->pay_amount;
//                })
//                ->addColumn('order_date', function ($data) {
//                     return $data->created_at->format('d-m-Y');
//                })
//                ->addColumn('delivery_date', function ($data) {
//                    return $data->status == 'delivered' ? $data->updated_at->format('d-m-Y') : 'Not Delivered';
//                })
//                ->addColumn('status', function ($data) {
//                    return $data->status;
//                })
//                ->addColumn('action', function ($data) {
//                    return '<div class="inline-flex gap-1">
//                            <a href="javascript:void(0);" onclick="viewOrderDetails(' . $data->id . ')" class="btn bg-info text-white rounded">
//                                <i class="fa-solid fa-eye"></i>
//                            </a>
//
//                    </div>';
//                })
//                ->rawColumns(['order_code','order_date','order_status', 'action'])
//                ->make(true);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('order_code', function ($data) {
                    return '#' . $data->uuid;
                })
                ->addColumn('products', function ($data) {
                    $products = [];
                    $counter = 1; // Start a counter to number the items

                    // Loop through the orderItems to get the related medicines
                    foreach ($data->orderItems as $orderItem) {
                        // Ensure that the related medicine and its details exist
                        if ($orderItem->medicine && $orderItem->medicine->details) {
                            $productInfo = "{$counter}. " . $orderItem->medicine->title . ' -' . $orderItem->medicine->generic_name . '- ' . $orderItem->medicine->details->dosage .'('.$orderItem->medicine->details->form .')';
                            $products[] = "<span>{$productInfo}</span><br>";  // Adding the product info to the array
                            $counter++; // Increment the counter for numbering
                        }
                    }

                    // Return the concatenated products
                    return implode(' ', $products);  // Join the products with a space or another separator
                })

                ->addColumn('status_logs', function ($data) {
                    $logs = [];
                    $counter = 1;
                    foreach ($data->activityLogs as $activityLog) {
                        if ($activityLog->action) {
                            // Check for specific actions
                            if ($activityLog->action == 'placed') {
                                $logs[] = "<span>{$counter}. {$activityLog->action} ({$activityLog->created_at->format('y-m-d h:i A')})</span>";
                            }
                            if ($activityLog->action == 'cancelled') {
                                $logs[] = "<span>{$counter}. Cancelled Resogen: This order is a duplicate or other reason ({$activityLog->created_at->format('y-m-d h:i A')})</span>";
                            }
                            if ($activityLog->action == 'processing') {
                                $logs[] = "<span>{$counter}. Approved ({$activityLog->created_at->format('y-m-d h:i A')})</span>";
                            }
                            if ($activityLog->action == 'delivered') {
                                $logs[] = "<span>{$counter}. Dispatched by Pharmasyst ({$activityLog->created_at->format('y-m-d h:i A')})</span>";
                            }

                            // If action is not one of the above
                            if (!in_array($activityLog->action, ['placed', 'cancelled', 'processing', 'delivered'])) {
                                $role = $activityLog->user->role == 'pharmacist' ? 'Pharmacist' : 'Doctor';
                                $logs[] = "<span>{$counter}. {$role} ({$activityLog->created_at->format('y-m-d h:i A')})</span>";
                            }
                        } else {
                            // Default log entry if action is null
                            $role = $activityLog->user->role == 'pharmacist' ? 'Pharmacist' : 'Doctor';
                            $logs[] = "<span>{$counter}. {$role} ({$activityLog->created_at->format('y-m-d h:i A')})</span>";
                        }
                        $counter++;
                    }

                    // Return the concatenated products
                    return implode(' ', $logs);  // Join the products with a space or another separator
                })
                ->addColumn('order_total', function ($data) {
                    return $data->total_price;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                            <a href="javascript:void(0);" onclick="viewOrderDetails(' . $data->id . ')" class="btn bg-info text-white rounded">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                    </div>';
                })
                ->rawColumns(['order_code','products','status_logs','order_total', 'action'])
                ->make(true);
        }
        return view('backend.layouts.customer.order_sheet',compact('customer'));
    }

}
