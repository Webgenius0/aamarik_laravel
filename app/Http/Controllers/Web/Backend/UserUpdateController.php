<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserUpdateController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function userProfile()
    {
        // Get Setting Data
        return view('Backend.layout.setting.setting');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'language' => 'nullable|string',
        ]);

        try {
            $user = User::findOrfail(Auth::id());
            $user->name = $request->name;
            $user->language = $request->language;

            // Upload User Avatar
            if (!empty($request['avatar'])) {
                if (empty($user->avatar)) {
                    // Upload New User Avatar
                    $avatar = Helper::fileUpload($request->avatar, 'users', $user->name . '_' . $user->id);
                } else {
                    // Remove Old File
                    @unlink(public_path('/') . $user->avatar);

                    // Upload New User Avatar
                    $avatar = Helper::fileUpload($request->avatar, 'users', $user->name . '_' . $user->id);
                }
                $user->avatar = $avatar;
            }

            $user->save();

            flash()->success('Profile updated successfully.');
            return redirect()->route('admin.edit.profile');
        } catch (\Exception $exception) {
            flash()->error($exception->getMessage());
            return redirect()->route('admin.edit.profile');
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function userPassword()
    {
        // Get Setting Data
        return view('Backend.layout.setting.setting');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userPasswordUpdate(Request $request)
    {

        $messages = [
            'old_password.required' => 'The old password is required.',
            'old_password.string' => 'The old password must be a string.',
            'old_password.min' => 'The old password must be at least :min characters.',
            'new_password.required' => 'The new password is required.',
            'new_password.string' => 'The new password must be a string.',
            'new_password.min' => 'The new password must be at least :min characters.',
            'confirm_password.required' => 'The confirm password is required.',
            'confirm_password.string' => 'The confirm password must be a string.',
            'confirm_password.same' => 'The confirm password must match the new password.',
            'confirm_password.min' => 'The confirm password must be at least :min characters.',
        ];

        $request->validate([
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:new_password|min:6',
        ], $messages);

        if (Hash::check($request->old_password, Auth::user()->password)) {
            User::findOrFail(Auth::user()->id)->update(['password' => Hash::make($request->new_password)]);
            flash()->success('Profile Update successfully.');
            return redirect()->route('admin.edit.profile');
        } else {
            flash()->error('The old password is not correct.');
            return redirect()->back();
        }
    }
}
