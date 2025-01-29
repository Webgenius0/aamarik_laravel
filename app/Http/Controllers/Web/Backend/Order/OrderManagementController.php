<?php

namespace App\Http\Controllers\Web\Backend\Order;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\FAQ;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Customer;
use Stripe\Stripe;
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
            $data = Order::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('order_code', function ($data) {
                    return '#' . $data->uuid;
                })
                ->addColumn('order_date', function ($data) {
                    return $data->created_at->format('d-m-Y');
                })
                ->addColumn('delivery_date', function ($data) {
                    return $data->status == 'delivered' ? $data->updated_at->format('d-m-Y') : 'Not Delivered';
                })
                ->addColumn('order_status', function ($data) {
                    $statusOptions =  ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'failed']; // Add your own statuses
                    $optionsHtml = '';
                    foreach ($statusOptions as $status) {
                        $selected = $data->status == $status ? 'selected' : '';
                        $optionsHtml .= "<option value='{$status}' {$selected}>{$status}</option>";
                    }

                    return "
                       <select class='form-select' id='status-dropdown-{$data->id}' onchange='updateOrderStatus({$data->id})'>
                            {$optionsHtml}
                        </select>
                    ";
                })
                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                            <a href="javascript:void(0);" onclick="viewOrderDetails(' . $data->id . ')" class="btn bg-info text-white rounded">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->uuid . ')" class="btn bg-danger text-white rounded" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                    </div>';
                })
                ->rawColumns(['order_code','order_date','order_status', 'action'])
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
                'order_date' => $order->created_at->format('Y-m-d'),
                'delivery_date' => $order->delivery_date ?? 'Not Assigned',
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
                'total_price' => $order->total_price,
                'note' => $order->note ?? null,
            ]
        ]);
    }

    /**
     * destroy Order
     */
    public function destroy($id)
    {
        // Find order with uuid
        $order = Order::with(['user', 'treatment', 'orderItems', 'review', 'billingAddress', 'assessmentsResults'])->where('uuid', $id)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        // Get order user stripe_customer_id
        $customerID = $order->stripe_payment_id;

        // Get order subscription id
        $subscriptionID = $order->subscription_id;

        if ($order->subscription && $subscriptionID) {
            try {
                $subscription = \Stripe\Subscription::retrieve($subscriptionID);

                if (!$subscription || empty($subscription->data)) {
                    return response()->json(['success' => false, 'message' => 'Subscription not found'], 404);
                }

                // Cancel the subscription if not already cancelled
                if ($subscription->status != 'canceled') {
                    if ($subscription->customer == $customerID) {
                        $subscription->cancel();
                    }
                }
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error cancelling subscription: ' . $e->getMessage()], 500);
            }
        }

         //if order has prescription
         if ($order->prescription && file_exists(public_path($order->prescription))) {
            unlink(public_path($order->prescription));
         }

        // Delete order review
        if ($order->review) {
            $order->review->delete();
        }

        // Delete order items
        $order->orderItems()->delete();

        // Delete order billing address
        if ($order->billingAddress) {
            $order->billingAddress->delete();
        }

        // Delete order assessments results
        if ($order->assessmentsResults) {
            $order->assessmentsResults->delete();
        }

        // Delete order
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Order deleted successfully.']);
    }


}
