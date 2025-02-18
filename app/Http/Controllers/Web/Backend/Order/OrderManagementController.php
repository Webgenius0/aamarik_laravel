<?php

namespace App\Http\Controllers\Web\Backend\Order;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AssessmentResult;
use App\Models\Coupon;
use App\Models\FAQ;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Customer;
use Stripe\Stripe;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;


class OrderManagementController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    /**
     * Display list of orders
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Group by type
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
            ])->latest()->get();


//            return DataTables::of($data)
//                ->addIndexColumn()
//                ->addColumn('order_code', function ($data) {
//                    return '#' . $data->uuid;
//                })
//                ->addColumn('order_date', function ($data) {
//                    return $data->created_at->format('d-m-Y');
//                })
//                ->addColumn('delivery_date', function ($data) {
//                    return $data->status == 'delivered' ? $data->updated_at->format('d-m-Y') : 'Not Delivered';
//                })
//                ->addColumn('order_status', function ($data) {
//                    $statusOptions =  ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'failed']; // Add your own statuses
//                    $optionsHtml = '';
//                    foreach ($statusOptions as $status) {
//                        $selected = $data->status == $status ? 'selected' : '';
//                        $optionsHtml .= "<option value='{$status}' {$selected}>{$status}</option>";
//                    }
//
//                    return "
//                       <select class='form-select' id='status-dropdown-{$data->id}' onchange='updateOrderStatus({$data->id})'>
//                            {$optionsHtml}
//                        </select>
//                    ";
//                })
//                ->addColumn('action', function ($data) {
//                    return '<div class="inline-flex gap-1">
//
//                            <a href="javascript:void(0);" onclick="viewOrderDetails(' . $data->id . ')" class="btn bg-info text-white rounded">
//                                <i class="fa-solid fa-eye"></i>
//                            </a>
//
//                            <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
//                                <i class="fa-solid fa-trash"></i>
//                            </a>
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

                            <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                    </div>';
                })
                ->rawColumns(['order_code','products','status_logs','order_total','action'])
                ->make(true);
        }
        return view('backend.layouts.Order.index');
    }



    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        if($request->status == 'delivered'){
            $order->touch();
        }

        return response()->json(['success' => true, 'message' => 'Order status updated successfully.']);
    }


    /**
     * Show order details
     */
    public function show($id)
    {
        $order = Order::with(['user', 'treatment', 'orderItems', 'billingAddress'])->find($id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }


        return response()->json([
            'success' => true,
            'data' => [
                'uuid' => $order->uuid,
                'status' => $order->status,
                'order_date' => $order->created_at->format('Y-m-d  h:i A'),
                'delivery_date' => $order->status == 'delivered' ? $order->updated_at->format('Y-m-d  h:i A') : 'Not Delivered',
                'user' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'billing_address' => [
                    'name' => $order->billingAddress->name ?? '',
                    'email' => $order->billingAddress->email ?? '',
                    'address' => $order->billingAddress->address ?? '',
                    'contact' => $order->billingAddress->contact ?? '',
                    'city' => $order->billingAddress->city ?? '',
                    'postcode' => $order->billingAddress->postcode ?? '',
                    'gp_number' => $order->billingAddress->gp_number ?? '',
                    'gp_address' => $order->billingAddress->gp_address ?? '',
                ],
                'order_items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->medicine->title,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price,
                    ];
                }),
                'treatment' => [
                    'id' => optional($order->treatment)->id,
                    'name' => optional($order->treatment)->name,
                ],
                'sub_total' => $order->sub_total,
                'discount' => $order->discount,
                'shipping_charge' => $order->shipping_charge,
                'tax' => $order->tax,
                'total_price' =>   number_format($order->sub_total - $order->discount + $order->royal_mail_tracked_price + $order->shipping_charge + $order->tax, 2, '.', ''),
                'note' => $order->note ?? null,
                'prescription' => asset($order->prescription) ?? null,
            ]
        ]);
    }

    /**
     * destroy Order
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Find order with uuid
            $order = Order::with(['user', 'treatment', 'orderItems', 'review', 'billingAddress'])->find($id);

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }


            // Get order user stripe_customer_id
            $customerID = $order->user->stripe_customer_id;

            // Get order subscription id
            $subscriptionID = $order->subscription_id;

           // If the order has a subscription, attempt to cancel it
            if ($subscriptionID) {
                try {
                    $subscription = \Stripe\Subscription::retrieve($subscriptionID);

                    if($subscription->status === 'active' && $subscription->customer === $customerID) {
                        $subscription->cancel();
                        Log::info('Subscription canceled successfully');
                    }


                } catch (\Stripe\Exception\ApiErrorException $e) {
                    DB::rollBack();

                    return response()->json(['success' => false, 'message' => 'Error cancelling subscription: ' . $e->getMessage()], 500);
                }
            }

            // If order has a prescription, delete it
            if ($order->prescription && file_exists(public_path($order->prescription))) {
                unlink(public_path($order->prescription));
            }

            // Delete associated review
            if ($order->review) {
                $order->review->delete();
            }

            // Delete associated order items
            $order->orderItems()->delete();

            // Delete associated billing address
            if ($order->billingAddress) {
                $order->billingAddress->delete();
            }

            // Delete associated assessment results
            AssessmentResult::where('order_id', $order->id)->delete();

            // Delete the order itself
            $order->delete();

            DB::commit(); // Commit the transaction if everything goes well

            return response()->json(['success' => true, 'message' => 'Order deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of error
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }




    /**
     * update order note
     */
    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string|max:1500',
        ]);

        $order = Order::where('uuid', $id)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }
        $order->note = $request->note;
        $order->save();
        return response()->json(['success' => true, 'message' => 'Order updated successfully.']);
    }

    /**
     * Update order shipping address
     */
    public function updateAddress(Request $request, $id)
    {
        $order = Order::with('billingAddress')->where('uuid', $id)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Error updating shipping address.']);
        }
        $order->billingAddress->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'contact' => $request->contact,
            'city' => $request->city,
            'postcode' => $request->postcode,
            'gp_number' => $request->gp_number,
            'gp_address' => $request->gp_address,
        ]);

        return response()->json(['success' => true, 'message' => 'Shipping address updated successfully.']);
    }





}
