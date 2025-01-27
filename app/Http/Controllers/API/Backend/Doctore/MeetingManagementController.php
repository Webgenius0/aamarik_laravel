<?php

namespace App\Http\Controllers\API\Backend\Doctore;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeetingManagementController extends Controller
{
    use apiresponse;

    /**
     * meeting information store in database and send mail notification in order user
     */
    public function store(Request $request,$id) //id mean uuid
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'link'        => 'nullable|url',
            'date'        => 'nullable|date',
            'time'        => 'nullable|date_format:H:i',
        ]);
        // If validation fails, return error message
        if ($validator->fails()) {
            return $this->sendError('Validation error:'.$validator->errors()->first(),[], 422); // Change the HTTP code if needed
        }

        // Retrieve validated data
        $validatedData = $validator->validated();

        try {
             $meeting = Meeting::create([
                 'user_id' => $id,
                 'title' => $validatedData['title'],
                 'description' => $validatedData['description'],
                 'link' => $validatedData['link'],
                 'date' => $validatedData['date'],
                 'time' => $validatedData['time'],
             ]);
        }
    }
}
