<?php

namespace App\Http\Controllers\API;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Verse;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use apiresponse;

    // Update User Information
    /**
     * Update user primary info
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserInfo(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'        => 'nullable|string|max:255',
            'username'    => 'nullable|string|max:255',
            'latitude'    => 'nullable|string|max:255',
            'longitude'   => 'nullable|string|max:255',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'avatar_path' => 'nullable|string',

        ]);

        if ($validation->fails()) {
            return $this->error([], $validation->errors(), 500);
        }

        try {
            $user = User::where('id', Auth::id())->first();
            $user->name      = $request->name ?? $user->name;
            $user->username  = $request->username ?? $user->username;
            $user->latitude  = $request->latitude ?? $user->latitude;
            $user->longitude = $request->longitude ?? $user->longitude;

            if ($request->hasFile('avatar')) {
                $url = Helper::fileUpload($request->file('avatar'), 'users', $user->name . "-" . time());
                $user->avatar = $url;
            } elseif ($request->avatar_path) {
                $user->avatar = $request->avatar_path; // Save predefined path
            }

            $user->save();
            return $this->success([], "User updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
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
            'new_password' => 'required|string|max:255',
        ]);

        if ($validation->fails()) {
            return $this->error([], $validation->errors(), 500);
        }

        try {
            $user = User::where('id', Auth::id())->first();
            if (password_verify($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return $this->success([], "Password changed successfully", 200);
            } else {
                return $this->error([], "Old password is incorrect", 500);
            }
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
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
        return $this->success([
            'notifications' => $notifications,
        ], "Notifications fetched successfully", 200);
    }

    /**
     * Delete User
     * @return \Illuminate\Http\Response
     */
    public function deleteUser()
    {

        $user = User::where('id', Auth::id())->with('bookmarks', 'communities', 'duaBookmarks', 'haditBookmarks', 'journals', 'message', 'prayers', 'replies')->first();

        if ($user) {
            // Delete related data if the user exists
            $user->bookmarks()->delete();
            $user->communities()->delete();
            $user->duaBookmarks()->delete();
            $user->haditBookmarks()->delete();
            $user->journals()->delete();
            $user->message()->delete();
            $user->prayers()->delete();
            $user->replies()->delete();
            $user->delete();

            return $this->success([], "User deleted successfully", 200);
        } else {
            return $this->error("User not found", 404);
        }
    }
}
