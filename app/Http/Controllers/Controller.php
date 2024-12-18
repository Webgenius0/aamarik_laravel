<?php

namespace App\Http\Controllers;

abstract class Controller
{
    // Success response
    public function sendResponse($data = [], $message, $code = 200)
    {
        $response = [
            'status' => true,
            'message' => $message,
            'code' => $code,
        ];
        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    // Error response
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'status' => false,
            'message' => $error,
            'code' => $code,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
