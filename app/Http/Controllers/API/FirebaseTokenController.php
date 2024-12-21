<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FirebaseTokensResource;
use App\Models\FirebaseTokens;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class FirebaseTokenController extends Controller
{
    /**
     * News Serve For Frontend
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'nullable|exists:users,id',
            'token'     => 'required|string',
            'device_id' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
        try {
            $user = FirebaseTokens::where('user_id', $request->user_id)->where('device_id', $request->device_id)->first();
            if ($user) {
                $user->delete();
            }

            $data = new FirebaseTokens();
            $data->user_id   = $request->user_id;
            $data->token     = $request->token;
            $data->device_id = $request->device_id;
            $data->is_active = $request->is_active;
            $data->save();
            //resturn response
            return $this->sendResponse(new FirebaseTokensResource($data), 'Token saved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error saving token:' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Get Single Record
     */
    public function getToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required|integer',
            'device_id' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
        $user_id   = $request->user_id;
        $device_id = $request->device_id;
        $data = FirebaseTokens::where('user_id', $user_id)->where('device_id', $device_id)->first();
        if (!$data) {
            return $this->sendResponse((object)[], 'No records found');
        }

        return $this->sendResponse(new FirebaseTokensResource($data), 'Token fetched successfully');
    }

    /**
     * Delete Token Single Record
     */
    public function deleteToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required|integer',
            'device_id' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $user = FirebaseTokens::where('user_id', $request->user_id)->where('device_id', $request->device_id);
        if ($user) {
            $user->delete();
            //resturn response
            return $this->sendResponse([], 'Token deleted successfully');
        } else {
            //resturn response
            return $this->sendResponse((object)[], 'No records found');
        }
    }
}
