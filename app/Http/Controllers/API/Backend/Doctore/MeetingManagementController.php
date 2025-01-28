<?php

namespace App\Http\Controllers\API\Backend\Doctore;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeetingResource;
use App\Models\Meeting;
use App\Models\Order;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeetingManagementController extends Controller
{
    use apiresponse;


    /**
     * list of meeting
     */
    public function index()
    {
        $meetings = Meeting::with('user')->latest()->get();
        if ($meetings->isEmpty()) {
            return $this->sendError('Meetings not found.');
        }
        return $this->sendResponse(MeetingResource::collection($meetings), 'Meetings retrieved successfully.');
    }

    /**
     * meeting information store in database and send mail notification in order user
     */
    public function store(Request $request,$id) //id mean uuid
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'link'        => 'required|url',
            'date'        => 'required|date_format:Y-m-d', // Ensures the date is in YYYY-MM-DD format
            'time'        => 'required|date_format:H:i:s', // Ensures the time is in HH:MM:SS format
        ]);
        // If validation fails, return error message
        if ($validator->fails()) {
            return $this->sendError('Validation error:'.$validator->errors()->first(),[], 422); // Change the HTTP code if needed
        }

        // Retrieve validated data
        $validatedData = $validator->validated();
        //find order with order uuid
        $order = Order::where("uuid",$id)->first();

        if(!$order){
            return $this->sendError("Order not found",[],404);
        }

        try {
            $meeting = Meeting::create([
                'user_id' => $order->user_id,
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'link' => $validatedData['link'],
                'date' => $validatedData['date'],
                'time' => $validatedData['time'],
            ]);

             //success response
            return  $this->sendResponse([],'Meeting created successfully.',201);
        }catch (\Exception $exception){
            return $this->sendError('Failed to create meeting',[],422);
        }
    }

    /**
     * update meeting status
     */
    public function updateStatus(Request $request,$id)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
           'status'    => 'required|in:scheduled,completed,canceled',
        ]);
        // If validation fails, return error message
        if ($validator->fails()) {
            return $this->sendError('Validation error:'.$validator->errors()->first(),[], 422); // Change the HTTP code if needed
        }

        // Retrieve validated data
        $validatedData = $validator->validated();

        try {
            $meeting = Meeting::find($id);
            if(!$meeting){
                return $this->sendError("Meeting not found",[],404);
            }
            $meeting->status = $validatedData['status'];
            $meeting->save();
            return $this->sendResponse([],'Meeting status updated successfully.');
        }catch (\Exception $exception){
            return $this->sendError('Failed to update meeting',[],422);
        }
    }

    /**
     * Delete meeting with meeting
     */
    public function deleteMeeting($id)
    {
        try {
            $meeting = Meeting::find($id);
            if(!$meeting){
                return $this->sendError("Meeting not found",[],404);
            }
            $meeting->delete();
            return $this->sendResponse([],'Meeting Deleted successfully.');
        }catch (\Exception $exception){
            return $this->sendError('Failed to Deleted meeting',[],422);
        }
    }
}

