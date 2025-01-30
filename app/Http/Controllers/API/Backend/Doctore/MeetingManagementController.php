<?php

namespace App\Http\Controllers\API\Backend\Doctore;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeetingResource;
use App\Models\Meeting;
use App\Models\Order;
use App\Traits\apiresponse;
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


        //generate meeting schedule link
        $data = $this->generateMeetingLink($validatedData,$order);



        try {
            $meeting = Meeting::create([
                'user_id' => $order->user_id,
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'link' => $data['onlineMeeting']['joinUrl'],
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
     * Generate meeting schedule link
     */
    private function generateMeetingLink($validatedData, $order)
    {
        // Get Microsoft Access Token
//        $accessToken = $this->getMicrosoftAccessToken();

//        if (!$accessToken) {
//            return $this->sendError('Failed to get access token', [], 500);
//        }

        $tokenRequestContext = new ClientCredentialContext(
            env('MS_TENANT_ID'),
            env('MS_CLIENT_ID'),
            env('MS_CLIENT_SECRET'),
        );

        $graphServiceClient = new GraphServiceClient($tokenRequestContext,['https://graph.microsoft.com/.default']);



        // Meeting Data
        $meetingData = [
            "subject" => $validatedData['title'],
            "body" => [
                "contentType" => "HTML",
                "content" => $validatedData['description'],
            ],
            "start" => [
                "dateTime" => $validatedData['date'] . 'T' . $validatedData['time'],
                "timeZone" => "UTC",
            ],
            "end" => [
                "dateTime" => date('Y-m-d\TH:i:s', strtotime('+1 hour', strtotime($validatedData['date'] . 'T' . $validatedData['time']))),
                "timeZone" => "UTC",
            ],
            "attendees" => [
                [
                    "emailAddress" => [
                        "address" => $order->user->email,
                        "name" => $order->user->name,
                    ],
                    "type" => "required",
                ],
            ],
            "isOnlineMeeting" => true,
            "onlineMeetingProvider" => "teamsForBusiness",
        ];

       $onlineMeeting = new OnlineMeeting();
       $onlineMeeting->setAdditionalData($meetingData);

        // Create Meeting via Microsoft Graph API
        try {

            $response = $graphServiceClient->me()->onlineMeetings()->post($onlineMeeting);
dd($response);
//            return $data;
        } catch (\Exception $e) {
            return $this->sendError('Failed to create meeting: ' . $e->getMessage(), [], 500);
        }
    }



    /**
     * Get Microsoft Graph API Access Token
     */
    private function getMicrosoftAccessToken()
    {
        $client = new Client();

        try {
            $response = $client->post(env('MS_AUTHORITY') . '/' . env('MS_TENANT_ID') . '/oauth2/v2.0/token', [
                'form_params' => [
                    'client_id' => env('MS_CLIENT_ID'),
                    'client_secret' => env('MS_CLIENT_SECRET'),
                    'grant_type' => 'client_credentials',
                    'scope' => 'https://graph.microsoft.com/.default',
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['access_token'])) {
                return $data['access_token'];
            } else {
                throw new \Exception("Error retrieving token: " . json_encode($data));
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to get access token: ' . $e->getMessage(), [], 500);
        }
    }
    public function getToken()
    {
       return new GraphServiceClient(
            env('MS_TENANT_ID'),
            env('MS_CLIENT_ID'),
            env('MS_CLIENT_SECRET'),
        );
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

