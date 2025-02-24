<?php

namespace App\Http\Controllers\API\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helper;
use App\Traits\apiresponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    use apiresponse;
    public function RedirectToProvider($provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function HandleProviderCallback($provider): void
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();
        // dd($socialUser);
    }

    public function SocialLogin(Request $request): JsonResponse
    {
        dd($request);
        $request->validate([
            'token'    => 'required',
            'provider' => 'required|in:google',
        ]);


        try {
            $provider   = $request->provider;
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($request->token);

            if ($socialUser) {
                $user      = User::where('email', $socialUser->email)->first();
                $isNewUser = false;

                if (!$user) {
                    $password = Str::random(16);

                    $user     = User::create([
                        'name'              => $socialUser->getName(),
                        'email'             => $socialUser->getEmail(),
                        'date_of_birth'     =>  null,
                        'role'              => 'user',
                        'password'          => bcrypt($password),
                        'email_verified_at' => now(),
                    ]);
                    $isNewUser = true;
                }

                Auth::login($user);

                // Evaluate the message based on the $isNewUser condition
                $message = $isNewUser ? 'User registered successfully' : 'User logged in successfully';

                // Create token for user
                $token = JWTAuth::fromUser($user);

                // Now call the sendResponse method
                return $this->sendResponse(new UserResource($user), "{$message}", 200, $token);
            } else {
                return  $this->sendError('Unauthorized',[], 401);
            }
        } catch (\Exception $e) {
            return  $this->sendError('Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }
}
