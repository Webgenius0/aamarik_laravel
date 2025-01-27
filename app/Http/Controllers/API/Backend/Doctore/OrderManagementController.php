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
            if (!in_array($user->role, ['user', 'pharmacist']))
            {
                $order->note   = $validatedData['note'];
            }
            $order->save();

            return $this->sendResponse([],'Order status or note updated successfully.',200);
        }catch (\Exception $e) {
            return $this->sendError('Failed to update Order status.',[],422);
        }
    }
}
