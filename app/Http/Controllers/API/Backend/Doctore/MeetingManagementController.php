<?php

namespace App\Http\Controllers\API\Backend\Doctore;

use App\Action\ZoomMeeting;
use App\Http\Controllers\Controller;
use App\Http\Resources\MeetingResource;
use App\Models\Meeting;
use App\Models\Order;
use App\Traits\apiresponse;
use App\Traits\ZoomMeetingTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Microsoft\Graph\Generated\Models\OnlineMeeting;
use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Abstractions\ApiException;
use Microsoft\Kiota\Abstractions\Serialization\ParseNode;
use Microsoft\Kiota\Authentication\Oauth\AuthorizationCodeContext;
use Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext;

class MeetingManagementController extends Controller
{
    use apiresponse,ZoomMeetingTrait;

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
        $order = Order::with('user')->where("uuid",$id)->first();

        if(!$order){
            return $this->sendError("Order not found",[],404);
        }


        $meetingDate = Carbon::parse($validatedData['date']);
        // Check if the meeting date is today or in the future
        if (!$meetingDate->isToday() && !$meetingDate->isFuture()) {
            return $this->sendError('Meeting date must be today or in the future.');
        }


        $data = [
            'topic'      => $validatedData['title'],
            'description'=> $validatedData['description'],
            'start_time' => $validatedData['date'] . ' ' . $validatedData['time'],
            'duration'   => 60,
            'host_video' => 1,
            'participant_video' => 1,
        ];

        //generate meeting schedule link
        $zoomMeeting = $this->createMeeting($data);

        if (empty($zoomMeeting) || !$zoomMeeting) {
            return $this->sendError('Zoom meeting not found.');
        }



        $zoomMeetingDate = Carbon::parse($zoomMeeting['start_time'])->format('Y-m-d');
        $zoomMeetingTime = Carbon::parse($zoomMeeting['start_time'])->format('h:i A');


        try {
            $meeting = Meeting::create([
                'meeting_id' => $zoomMeeting['id'],
                'user_id' => $order->user_id,
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'link' => $zoomMeeting['join_url'],
                'date' => $zoomMeetingDate,
                'time' => $zoomMeetingTime,
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

