<?php

use App\Http\Controllers\API\Auth\AuthenticationController;
use App\Http\Controllers\API\Auth\ForgetPasswordController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\FirebaseTokenController;
use App\Http\Controllers\API\Frontend\CMSController;
use App\Http\Controllers\API\Frontend\SectionController;
use App\Models\FirebaseTokens;

/**
 * Auth Routes
 */
Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});


//! Route for forget password
Route::controller(ForgetPasswordController::class)->group(function () {
    Route::post('/forget-password', 'forgetPassword');
    Route::post('/verify-otp-password', 'verifyResetCode');
    Route::post('/reset-password', 'resetPassword');
    Route::post('resend-otp', 'resendOtp');
}); // End of forget password Controller



Route::group(['middleware' => ['jwt.verify']], function () {

    //! Route for User Auth  Controller
    Route::controller(AuthenticationController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/me', 'me');
    }); // End of User Auth Controller



    //! Route for User Auth Profile  Controller
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/me', 'me');
        Route::post('/user-update',  'updateUserInfo');
        Route::delete('/delete/user', 'deleteUser');
        Route::get('/my-notifications', 'getMyNotifications');
        Route::post('/change-password', 'changePassword');
    }); // End of User Auth Profile Controller




});



//! Route for CMS controller
Route::controller(CMSController::class)->group(function () {
    Route::get('/cms/get-banner-page-data', 'homeBanner');
    Route::get('/cms/get-personalized-page-data', 'personalized');
}); // End of CMS Controller

//! Route for CMS controller
Route::controller(SectionController::class)->group(function () {
    Route::post('/section/data', 'getSection');
}); // End of CMS Controller


//! Route for Firebase Token  Controller
Route::controller(FirebaseTokenController::class)->group(function () {
    Route::post('/firebase/token/add', 'store');
    Route::post('/firebase/token/get', 'getToken');
    Route::post('/firebase/token/detele', 'deleteToken');
}); // End of Firebase Token Controller
