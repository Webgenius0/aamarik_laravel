<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\apiresponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

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


                    // Get the birthdate from the social provider response
                    $dateOfBirth = $socialUser->user['birthday'] ?? null;  // Birthdate provided by Google

                    // If a birthdate exists, format it as Y-m-d (e.g., 1990-01-01)
                    if ($dateOfBirth) {
                        $dateOfBirth = Carbon::parse($dateOfBirth)->format('Y-m-d');
                    }

                    $user     = User::create([
                        'name'              => $socialUser->getName(),
                        'email'             => $socialUser->getEmail(),
                        'date_of_birth'     =>  $dateOfBirth,
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
