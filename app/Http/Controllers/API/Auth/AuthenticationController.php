<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\apiresponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends Controller
{
    use apiresponse;

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'date_of_birth' => ['required', 'date_format:m/d/Y'],
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
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), [], 422);
        }

        try {
            $date = Carbon::createFromFormat('m/d/Y',  $request->date_of_birth)->format('Y-m-d');
            // Create new user
            $user = User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'date_of_birth' => $date,
                'role'          => 'user',
            ]);

            // Create token for user
            $token = JWTAuth::fromUser($user);

            return $this->sendResponse(new UserResource($user), 'User successfully registered.', 200, $token);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Failed to register user', $th->getMessage());
            return $this->sendError('Failed to register user.', [], 500);
        }
    }


    /**
     *  Login user
     *  @param  Request $request
     *  @return Response
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt to log the user in
        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->sendError('Invalid credentials', [], 401);
        }

        $user = Auth::user();

        return $this->sendResponse(new UserResource($user), 'User logged in successfully.', 200, $token);
    }



    /**
     * Log out the user (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            // Invalidate the token
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->sendResponse([], 'User logged out successfully', 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to log out, please try again.' . $e->getMessage(), [], 400);
        }
    }



    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            // Refresh the token
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return $this->sendResponse([], 'Token refreshed successfully', 200, $newToken);
        } catch (\Exception $e) {
            return $this->sendError('Failed to refresh token, please try again.' . $e->getMessage(), [], 400);
        }
    }
}
