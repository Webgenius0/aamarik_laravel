<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Traits\apiresponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{
    use apiresponse;

    /**
     * Forget Password Controller
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return $this->sendError([], $validator->errors()->first(), 422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->sendError('User not found', [], 404);
        }


        // Generate a 4-digit random verification code
        $code = rand(1000, 9999);
        $user->update([
            'reset_code' => $code,
            'reset_code_expires_at' => now()->addMinutes(5), // expires in 5 minutes
        ]);

        // Send email with code
        Notification::send($user, new PasswordResetNotification($code, 'reset'));

        return $this->sendResponse([], 'Please check your email for the OTP to reset your password.', 200);
    }


    /**
     * Verify code
     */
    public function verifyResetCode(Request $request)
    {
        // Validation
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code'   => 'required|digits:4|integer',
        ]);
        // If validation fails, return error message
        if ($validation->fails()) {
            Log::warning('Reset code validation failed');
            return $this->sendError($validation->errors()->first(), [], 422); // Change the HTTP code if needed
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || $user->reset_code !== $request->code) {
                // Return response
                return $this->sendError('Invalid reset code', [], 400);
            }

            // Check if the code is expired
            if (Carbon::now()->greaterThan($user->reset_code_expires_at)) {
                return  $this->sendError([], 'Reset code has expired', 400);
            }

            // Code is valid
            return $this->sendResponse([], 'Reset code is valid', 200);
        } catch (\Throwable $e) {
            //throw $e;
            Log::error('Reset code sent error: ' . $e->getMessage());
            // Return a user-friendly error message
            return $this->sendError('verify Reset Code Error', [], 500);
        }
    }

    /**
     *
     */

    // Reset password
    public function resetPassword(Request $request)
    {
        // Validation
        $validation = Validator::make($request->all(), [
            'email'    => 'required|email|exists:users,email',
            'code'     => 'required|digits:4|integer',
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
            Log::warning('reset Password Validation Error');
            return $this->sendError($validation->errors()->first(), [], 422); // Change the HTTP code if needed
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || $user->reset_code !== $request->code) {
                // Return response
                return $this->sendError('Invalid reset code', [], 400);
            }

            if (Carbon::now()->greaterThan($user->reset_code_expires_at)) {
                return $this->sendError('Reset code has expired.', [], 400);
            }

            // Reset password
            $user->password              = Hash::make($request->password);
            $user->reset_code            = null; // Clear the reset code
            $user->reset_code_expires_at = null; // Clear the expiration time
            $user->save();

            // Return response
            return $this->sendResponse([], 'Password reset successfully.', 200);
        } catch (\Throwable $e) {
            //throw $e;
            Log::error('Reset code sent error: ' . $e->getMessage());
            // Return a user-friendly error message
            return $this->sendError('reset Password Error', ['error' => 'An error occurred while sending the reset Password. Please try again.'], 500);
        }
    }

    /**
     *  Resend Otp
     */
    public function resendOtp(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->sendError('User not found', [], 404);
        }

        // Generate a 4-digit random verification code
        $code = rand(1000, 9999);
        $user->update([
            'reset_code' => $code,
            'reset_code_expires_at' => now()->addMinutes(5), // expires in 5 minutes
        ]);

        // Send email with code
        Notification::send($user, new PasswordResetNotification($code, 'reset'));
        return $this->sendResponse([], 'Please check your email for the OTP to reset your password.', 200);
    }
}
