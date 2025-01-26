<?php

use App\Http\Controllers\API\Auth\AuthenticationController;
use App\Http\Controllers\API\Auth\ForgetPasswordController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\ProfileController;
use App\Http\Controllers\API\Backend\StripePaymentMethodController;
use App\Http\Controllers\API\Backend\UserController as BackendUserController;
use App\Http\Controllers\API\Frontend\CouponController;
use App\Http\Controllers\API\Frontend\DoctoreController;
use App\Http\Controllers\API\Frontend\FAQController;
use App\Http\Controllers\API\Frontend\MedicineController;
use App\Http\Controllers\API\Frontend\OrderManagement;
use App\Http\Controllers\API\Frontend\TreatmentController;
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

/**
 * Route for SocialLogin
 */
Route::post('/social-login',[\App\Http\Controllers\API\Auth\SocialLoginController::class,'SocialLogin']);


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

    //! Route for order management
    Route::controller(OrderManagement::class)->group(function () {
       Route::post('/order-checkout', 'orderCheckout');
    });


    //! Route for coupon controller
    Route::post('/apply-coupon',[CouponController::class, 'applyCoupon']);


    //! Route for stripe payment method
  Route::controller(StripePaymentMethodController::class)->group(function () {
        Route::post('/add/stripe/customer/payment-method', 'addMethodToCustomer');
        Route::get('/get/stripe/customer/payment-method', 'getCustomerPaymentMethods');
        Route::delete('/remove/stripe/customer/payment-method/{paymentMethodID}', 'removeCustomerPaymentMethod');
  });

  //! Route for user controlller
    Route::controller(BackendUserController::class)->group(function () {
        Route::get('/auth-review', 'getAuthReview');

    });

    Route::controller(\App\Http\Controllers\API\Backend\OrderManagementController::class)->group(function () {
        Route::post('/orders', 'index'); //get all orders
        Route::get('/order/{id}', 'show'); //show order
        Route::post('/order-review/{id}', 'storeOrderReview'); //review order user
        Route::get('/assessments-result', 'getAssessmentResult');
    });
});



//! Route for CMS controller
Route::controller(CMSController::class)->group(function () {
    Route::get('/cms/get-banner-page-data', 'homeBanner');
    Route::get('/cms/get-personalized-page-data', 'personalized');
    Route::get('/get-delivery-info-data', 'getDeliveryInfo');
    Route::get('/get-consultation-data', 'getConsultation');
}); // End of CMS Controller

//! Route for CMS controller
Route::controller(SectionController::class)->group(function () {
    Route::post('/section/data', 'getSection');
}); // End of CMS Controller


//! Route for faqs controller
Route::controller(FAQController::class)->group(function () {
 Route::get('/faq/supplement', 'index');
 Route::get('/faqs', 'faq');
});

//! Route for doctore controller
Route::controller(DoctoreController::class)->group(function () {
    Route::get('/doctores', 'index');
});

//! Route for medicine controller
Route::controller(MedicineController::class)->group(function () {
    Route::get('/medicines', 'index');
    Route::get('/medicine/{medicineID}/show', 'show');
    Route::get('/medicine/review', 'getReview');
    Route::get('/medicine/review-avarage', 'getAverageReviews');
});

//! Route for Firebase Token  Controller
Route::controller(FirebaseTokenController::class)->group(function () {
    Route::post('/firebase/token/add', 'store');
    Route::post('/firebase/token/get', 'getToken');
    Route::post('/firebase/token/detele', 'deleteToken');
}); // End of Firebase Token Controller


//! Route for Treatment
Route::controller(TreatmentController::class)->group(function () {
    Route::get('/treatments', 'index');
    Route::get('/treatment/servicess', 'treatmentServicess');
    Route::get('/treatment/{treatmentID}/detail', 'treatmentDetail');
    Route::get('/treatment/{treatmentID}/about', 'treatmentAbout');
    Route::get('/treatment/{treatmentID}/medicines', 'treatmentMedicines');
    Route::get('/treatment/{treatmentID}/consultation', 'treatmentConsultation');
});


