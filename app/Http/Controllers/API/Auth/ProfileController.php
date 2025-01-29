<?php

namespace App\Http\Controllers\API\Auth;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use apiresponse;

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        return $this->sendResponse(new UserResource($user), 'User retrieved successfully');
    }


    /**
     * Update user primary info
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserInfo(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'          => 'nullable|string|max:255',
            'email'         => 'nullable|email|max:255|unique:users,email,' . auth()->user()->id,
            'date_of_birth' => 'nullable|date_format:m/d/Y',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone'         => 'nullable|string',
            'gender'        => 'nullable|in:male,female,other',
            'address'       => 'nullable|string',
        ]);

        // If validation fails, return error message
        if ($validation->fails()) {
            return $this->sendError($validation->errors()->first(), [], 422); // Change the HTTP code if needed
        }


        try {
            $date = Carbon::createFromFormat('m/d/Y',  $request->date_of_birth)->format('Y-m-d');

            $user = User::where('id', Auth::id())->first();
            $user->name      = $request->name ?? $user->name;
            $user->email     = $request->email ?? $user->email;
            $user->date_of_birth  = $date ?? $user->date_of_birth;
            $user->phone      = $request->phone ?? $user->phone;
            $user->gender      = $request->gender ?? $user->gender;
            $user->address     = $request->address ?? $user->address;

            $user->avatar      =  $user->avatar ?? null;
            if ($request->hasFile('avatar')) {
                //delete user avatar
                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    unlink(public_path($user->avatar));
                }
                $url = Helper::fileUpload($request->file('avatar'), 'users', $user->name . "-" . time());
                $user->avatar = $url;
            }

            $user->save();
            return $this->sendResponse([], 'User info updated successfully');
        } catch (\Exception $e) {
            return $this->sendError(' Error updating user info', $e->getMessage(), 500);
        }
    }


    /**
     * Delete User
     * @return \Illuminate\Http\Response
     */
    public function deleteUser()
    {

        $user = User::where('id', Auth::id())->first();

        if ($user) {
            //delete user avatar
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }
            // Delete related data if the user exists
            $user->delete();

            return $this->sendResponse([], ' User deleted successfully');
        } else {
            return $this->sendError(' User not found', [], 404);
        }
    }



    /**
     * Get My Notifications
     * @return \Illuminate\Http\Response
     */
    public function getMyNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->get();
        return $this->sendResponse($notifications, 'Notifications fetched successfully');
    }



    /**
     * Change Password
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'old_password' => 'required|string|max:255',
            'password'      => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/[a-z]/',         // Must contain at least one lowercase letter
                'regex:/[A-Z]/',         // Must contain at least one uppercase letter
                'regex:/[0-9]/',         // Must contain at least one digit
                'regex:/[@$!%*#?&]/',    // Must contain at least one special character
            ],
        ]);


        // If validation fails, return error message
        if ($validation->fails()) {
            Log::error('Failed to change password.', $validation->errors()->toArray());
            return $this->sendError($validation->errors()->first(), [], 422); // Change the HTTP code if needed
        }

        try {
            $user = User::where('id', Auth::id())->first();
            if (password_verify($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();
                return $this->sendResponse([], 'Password changed successfully');
            } else {
                return $this->sendError('Old password is incorrect', [], 500);
            }
        } catch (\Exception $e) {
            return $this->sendError('Error changing password', [], 500);
        }
    }
}
