<?php

namespace App\Http\Controllers\API\Backend\Doctore;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\userOrderDetailsResource;
use App\Models\Order;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderManagementController extends Controller
{
    use apiresponse;

    /**
     * Get Order Overview
     * If current auth role is doctor and pharmacist this time get total order consultation and Order pending and order Delivered
     */
    public function  getOrderOverview()
    {
        //get current user
        $user = auth()->user();
        if (!$user) {
            return $this->sendError('Unauthorized access.', [], 401);
        }

        // Initialize variables
        $total = 0;
        $pending = 0;
        $delivered = 0;

        // Query based on user role
        if ($user->role == 'doctor' || $user->role == 'pharmacist') {
            // Get total order
            $total = Order::count();

            // Get pending and delivered orders for the user
            $pending = Order::whereNot('status', 'delivered')
                ->count();

            $delivered = Order::where('status', 'delivered')
                ->count();
        }else{
            return $this->sendError('Unauthorized access.', [], 403);
        }

        //return response
        return  $this->sendResponse([
            'total' => $total,
            'pending'      => $pending,
            'delivered'    => $delivered
        ], 'Orders Overview retrieved successfully.');
    }

    /**
     * Show order Details with order uuid
     */
    public  function  getOrderDetails(Request $request,$id) //$id mean uuid
    {
        try {
            //get current auth user
            $user = auth()->user();


           // Check if the user is authenticated and has a role of 'doctor' or 'pharmacist'
            if (!$user || !in_array($user->role, ['doctor', 'pharmacist'])) {
                return $this->sendResponse([], 'Unauthorized access. You are not allowed to view order details.', 403);
            }

            //find order with order id(uuid)
            $order = Order::with(['user','orderItems','orderItems.medicine','billingAddress'])->where('uuid',$id)->first();
            if (!$order) {
                return $this->sendError('Order not found.',[],404);
            }
            //old orders this order user
            $oldOrder =  Order::with(['user','orderItems','orderItems.medicine','billingAddress'])->whereNot('uuid',$id)->where('user_id',$order->user_id)->latest()->get();
            $response = [
                'order' => new OrderDetailsResource($order),
                'past_orders' => OrderDetailsResource::collection($oldOrder),
            ];

            return $this->sendResponse($response,'Order Details retrieved successfully.',200);
        }catch (\Exception $e) {
            return $this->sendError('Failed to retrieved Order Details',[]);
        }
    }

    /**
     * Update and add note or update status
     */
    public  function  updateOrderStatusNote(Request $request,$id) //$id mean uuid
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'note'   => 'nullable|string',
            'status' => 'nullable|in:processing,shipped,delivered,cancelled,failed', // List of valid status values
        ]);
        // If validation fails, return error message
        if ($validator->fails()) {
            return $this->sendError('Validation failed. Please check the provided details and try again.',$validator->errors()->toArray(), 422); // Change the HTTP code if needed
        }
        // Retrieve validated data
        $validatedData = $validator->validated();


        try {
            //get current auth
            $user = auth()->user();

            // Check if the user is authenticated and has a role of 'doctor' or 'pharmacist'
            if (!$user || !in_array($user->role, ['doctor', 'pharmacist'])) {
                return $this->sendResponse([], 'Unauthorized access. You are not allowed to view order details.', 403);
            }

            $order = Order::where('uuid',$id)->first();
            if (!$order) {
                return $this->sendError('Order not found.',[],404);
            }
            $order->status = $validatedData['status'];
            //store active log
            $this->activityLog($order->id,$validatedData['status']);
            if (!in_array($user->role, ['user', 'pharmacist']))
            {
                $order->note   = $validatedData['note'];
                //store active log
                $this->activityLog($order->id,$validatedData['status']);
            }
            $order->save();

            return $this->sendResponse([],'Order status or note updated successfully.',200);
        }catch (\Exception $e) {
            return $this->sendError('Failed to update Order status.',[],422);
        }
    }
}
