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
use App\Http\Controllers\API\FriendshipController;
use App\Http\Controllers\API\LeaderboardController;
use App\Http\Controllers\API\LocationGroupContoller;
use App\Http\Controllers\API\LocationGroupImageContoller;
use App\Http\Controllers\API\LocationReachContoller;
use App\Http\Controllers\API\MessagingController;
use App\Http\Controllers\API\NewsLetterController;
use App\Http\Controllers\API\WebHooksController;
use App\Http\Controllers\API\WishlistContoller;

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
    Route::post('logout', [UserAuthController::class, 'logout']);
    Route::get('me', [UserAuthController::class, 'me']);
    Route::post('refresh', [UserAuthController::class, 'refresh']);

    Route::delete('/delete/user', [UserController::class, 'deleteUser']);


    Route::post('change-password', [UserController::class, 'changePassword']);
    Route::post('user-update', [UserController::class, 'updateUserInfo']);

    // Get Notifications
    Route::get('/my-notifications', [UserController::class, 'getMyNotifications']);

    // Verse
    Route::get('/verse', [UserController::class, 'getVerse']);


    //! Route for location group
    Route::get('/location-groups', [LocationGroupContoller::class, 'index']);
    Route::get('/location-group/{groupID}', [LocationGroupContoller::class, 'show']);

    //! Route for location Reach
    Route::post('/location-reach', [LocationReachContoller::class, 'set_location_reach']);

    //! Route for location group image
    Route::get('/puzzles', [LocationGroupImageContoller::class, 'index']);

    //! Route for puzzles details
    Route::get('/puzzle/{puzzleID}', [LocationGroupImageContoller::class, 'show']);


    //! Route for wish list
    Route::get('/wishlist/toggle/{locationID}', [WishlistContoller::class, 'toggleWishlist']);
    Route::get('/wishlists', [WishlistContoller::class, 'index']);


    //! Route for Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index']);


    //! Route for friendship toggle
    Route::get('/friendship/toggle/{friendID}', [FriendshipController::class, 'friendship']);
    Route::get('/friendships', [FriendshipController::class, 'index']);//! Route for following friends

});


