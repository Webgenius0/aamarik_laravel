<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DuaController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\JournController;
use App\Http\Controllers\API\TrackerContoller;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\RepliesController;
use App\Http\Controllers\API\BookmarkController;
use App\Http\Controllers\API\UserAuthController;
use App\Http\Controllers\API\CommunityController;
use App\Http\Controllers\API\FirebaseTokenController;
use App\Http\Controllers\API\FriendshipController;
use App\Http\Controllers\API\LeaderboardController;
use App\Http\Controllers\API\LocationGroupContoller;
use App\Http\Controllers\API\LocationGroupImageContoller;
use App\Http\Controllers\API\LocationReachContoller;
use App\Http\Controllers\API\MessagingController;
use App\Http\Controllers\API\NewsLetterController;
use App\Http\Controllers\API\WebHooksController;
use App\Http\Controllers\API\WishlistContoller;
use App\Models\FirebaseTokens;

Route::controller(UserAuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');

    // Resend Otp
    Route::post('resend-otp', 'resendOtp');

    // Forget Password
    Route::post('forget-password', 'forgetPassword');
    Route::post('verify-otp-password', 'varifyOtpWithOutAuth');
    Route::post('reset-password', 'resetPassword');

    // Google Login
    Route::post('google/login', 'googleLogin');
});

Route::group(['middleware' => ['jwt.verify']], function () {

    //! Route for User  Controller
    Route::controller(UserController::class)->group(function () {
        Route::post('change-password', 'changePassword');
        Route::post('user-update',  'updateUserInfo');
        Route::get('/my-notifications', 'getMyNotifications');
        Route::delete('/delete/user', 'deleteUser');
    }); // End of User Controller


    //! Route for User Auth  Controller
    Route::controller(UserAuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'me');
        Route::post('refresh', 'refresh');
    }); // End of User Auth Controller


    //! Route for location group  Controller
    Route::controller(LocationGroupContoller::class)->group(function () {
        Route::get('/location-groups',  'index');
        Route::get('/location-group/{groupID}', 'show');
    }); // End of location group Controller


    //! Route for location Reach Controller
    Route::post('/location-reach', [LocationReachContoller::class, 'set_location_reach']);


    //! Route for location group image  Controller
    Route::controller(LocationGroupImageContoller::class)->group(function () {
        Route::get('/puzzles',  'index'); //! Route for location group image
        Route::get('/puzzle/{puzzleID}', 'show'); //! Route for puzzles details
    }); // End of location group image Controller


    //! Route for wish list  Controller
    Route::controller(WishlistContoller::class)->group(function () {
        Route::get('/wishlist/toggle/{locationID}', 'toggleWishlist');
        Route::get('/wishlists',  'index');
    }); // End of wish list Controller


    //! Route for Leaderboard  Controller
    Route::controller(LeaderboardController::class)->group(function () {
        Route::get('/leaderboard',  'index'); //! Route for Leaderboard
        Route::post('/leader', 'show'); //! Route for show Leader profile by ID or if not provided then show the current user profile
    }); // End of Leaderboard Controller



    //! Route for Leaderboard  Controller
    Route::controller(FriendshipController::class)->group(function () {
        Route::get('/friendship/toggle/{friendID}',  'friendship');        //! Route for friendship toggle
        Route::post('/friendships', 'index'); //! Route for  following friends with Leader profile by ID or if not provided then show the current user profile
        Route::post('/follower/friendships', 'follower');         //! Route for  follower friends with Leader profile by ID or if not provided then show the current user profile
    }); // End of Leaderboard Controller
});

//! Route for Firebase Token  Controller
Route::controller(FirebaseTokenController::class)->group(function () {
    Route::post('/firebase/token/add', 'store');
    Route::post('/firebase/token/get', 'getToken');
    Route::post('/firebase/token/detele', 'deleteToken');
}); // End of Firebase Token Controller
