<?php

//use App\Http\Controllers\API\Frontend\CMSController;
use App\Models\DuaCategory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Backend\DuaController;
use App\Http\Controllers\Web\Backend\VerseController;
use App\Http\Controllers\Web\Backend\SettingController;
use App\Http\Controllers\Web\Backend\settings\MailSettingsController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\MessagingController;
use App\Http\Controllers\Web\Backend\NewsLetterController;
use App\Http\Controllers\Web\Backend\UserUpdateController;
use App\Http\Controllers\Web\Backend\SocialMediaController;
use App\Http\Controllers\Web\Backend\CMSController;
use App\Http\Controllers\Web\Backend\DoctorController;
use App\Http\Controllers\Web\Backend\MedicineController;
use App\Http\Controllers\Web\Backend\TreatMentController;
use App\Http\Controllers\Web\Backend\coupon\CouponController;
use Illuminate\Support\Facades\Mail;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Edit Profile
Route::get('profile', [DashboardController::class, 'editProfile'])->name('admin.edit.profile');
Route::post('edit-profile', [UserUpdateController::class, 'update'])->name('admin.edit.profile.update');
Route::post('change-password', [UserUpdateController::class, 'userPasswordUpdate'])->name('admin.change.password');

// Setting
Route::post('setting-update', [SettingController::class, 'update'])->name('admin.setting.update');
Route::get('setting', [SettingController::class, 'index'])->name('admin.setting');
//Route::get('/mail/setting', [MailSettingsController::class, 'index'])->name('admin.mail.setting');


// Home Page Content Update
Route::get('home-page-update', [SettingController::class, 'homePageContents'])->name('admin.home.page.edit');
Route::post('home-page-update', [SettingController::class, 'homePageUpdate'])->name('admin.home.page.update');

//stripe Setting Controller
Route::get('mail/setting', [SettingController::class, 'mailSetting'])->name('mail.setting');
Route::post('mail/setting', [SettingController::class, 'mailSettingUpdate'])->name('mail.setting.update');

//Mail Setting Controller
Route::get('stripe/setting', [SettingController::class, 'stripeSetting'])->name('stripe.setting');
Route::post('stripe/setting', [SettingController::class, 'stripeSettingUpdate'])->name('stripe.setting.update');

/* // Social Media
Route::get('social-media/setting', [SocialMediaController::class, 'index'])->name('social.setting'); */

// Dynamic Page
Route::get('/dynamic-page/index', [SettingController::class, 'additional'])->name('dynamic.page.index');
Route::get('/dynamic-page/create', [SettingController::class, 'dynamicPageCreate'])->name('dynamic.page.create');
Route::post('/dynamic-page/create', [SettingController::class, 'dynamicPageStore'])->name('dynamic.page.store');
Route::get('/dynamic-page/update/{id}', [SettingController::class, 'dynamicPageEdit'])->name('dynamic.page.edit');
Route::post('/dynamic-page/update/{id}', [SettingController::class, 'dynamicPageUpdate'])->name('dynamic.page.update');
Route::get('/dynamic-page/delete/{id}', [SettingController::class, 'dynamicPageDelete'])->name('dynamic.page.delete');



// social media
Route::get('social-media/setting', [SocialMediaController::class, 'index'])->name('social.setting');
Route::get('social-media/media', [SocialMediaController::class, 'index'])->name('social.media');
Route::get('social-media/media/create', [SocialMediaController::class, 'create'])->name('social.media.create');
Route::post('social-media/media', [SocialMediaController::class, 'store'])->name('social.media.store');
Route::get('social-media/media/edit/{id}', [SocialMediaController::class, 'edit'])->name('social.media.edit');
Route::put('social-media/media/{id}', [SocialMediaController::class, 'update'])->name('social.media.update');
Route::get('social-media/media/status/{id}', [SocialMediaController::class, 'status'])->name('social.media.status');
Route::delete('social-media/media/{id}', [SocialMediaController::class, 'destroy'])->name('social.media.destroy');

//Route::controller(Faqs)->group(function () {
//
//
//})->middleware('auth,admin');

//FAQ
Route::controller(\App\Http\Controllers\Web\Backend\FaqController::class)->group(function (){
    Route::get('faqs','index')->name('faq.index');
    Route::post('faqs/store', 'store')->name('faq.store');
    Route::get('faq/status/update/{id}','updateStatus')->name('update.status.faq');
    Route::get('faqs/edit/{id}', 'edit')->name('faq.edit');
    Route::put('faqs/update/{id}', 'update')->name('faq.update');
    Route::delete('faqs/destroy/{id}','destroy')->name('faq.destroy');
})->middleware('auth,admin');

//cms banner
Route::get('/settings/banner',[ CMSController::class ,'banner'])->name('banner');
Route::get('/cms/home-section',[ CMSController::class ,'homeSection'])->name('home.section');
Route::put('/cms', [CmsController::class, 'update'])->name('cms.update');
Route::get('/cms/personalized', [CmsController::class, 'showPersonalized'])->name('cms.personalized');
Route::put('/cms/personalized', [CmsController::class, 'personalized'])->name('cms.personalized');

//cms section

Route::get('/cms/confediential', [CmsController::class, 'homeSection'])->name('cms.confediential');
Route::put('/cms/update', [CmsController::class, 'updateSection'])->name('section.update');
Route::put('/cms/working-process', [CmsController::class, 'updateWorkingProcess'])->name('cms.working.process');
//doctor section
//Route::get('/cms/doctor-section', [CmsController::class, 'doctorSection'])->name('cms.doctor.section');
Route::get('/doctor', [DoctorController::class, 'index'])->name('doctor.index');
Route::post('/doctor-add', [DoctorController::class, 'store'])->name('doctor.store');
Route::get('/doctor-create', [DoctorController::class, 'create'])->name('doctor.create-form');
Route::get('/doctor-edit/{id}', [DoctorController::class, 'edit'])->name('doctor.edit');
Route::put('/doctor-update/{id}', [DoctorController::class, 'update'])->name('doctor.update');
Route::delete('/doctor-delete/{id}', [DoctorController::class, 'destroy'])->name('doctor.delete');

//deprtment
Route::get('/department-form', [DoctorController::class, 'departmentCreateForm'])->name('department.create.form');
Route::get('/status-update/{id}', [DoctorController::class, 'updateDepartmentStatus'])->name('doctor.status.update');
Route::get('/department/show', [DoctorController::class, 'department'])->name('doctors.department');
Route::post('/department-add', [DoctorController::class, 'departmentStore'])->name('doctor.department.store');
Route::delete('/department-delete/{id}', [DoctorController::class, 'DestroyCategory'])->name('doctor.destroy.category');
//department for show Departmentlist

Route::get('/department', [DoctorController::class, 'getDeparments'])->name('doctor.department');

//Medicine section
Route::controller(MedicineController::class)->group(function () {
    Route::get('/medicine', 'index')->name('medicine.index');
    Route::post('/medicine', 'store')->name('medicine.store');
    Route::get('/medicine/edit/{id}', 'edit')->name('medicine.edit');
    Route::put('/medicine/update/{id}', 'update')->name('medicine.update');
    Route::delete('/medicine/delete/{id}', 'destroy')->name('medicine.destroy');
    Route::get('/medicine/status/update/{id}', 'updateStatus')->name('medicine.status.update');
});
//treatment section
Route::controller(TreatMentController::class)->group(function () {
    Route::get('/treatment', 'index')->name('treatment.index');
    Route::post('/treatment', 'store')->name('treatment.store');
    Route::get('/treatment/edit/{id}', 'edit')->name('treatment.edit');
    Route::put('/treatment/update/{id}', 'update')->name('treatment.update');
    Route::delete('/treatment/delete/{id}', 'destroy')->name('treatment.destroy');
    Route::get('/treatment/status/update/{id}', 'updateStatus')->name('treatment.status.update');
    //for list
    Route::get('/treatment/list', 'treatmentList')->name('treatment.list');
});

//coupons
Route::controller(CouponController::class)->group(function () {
    Route::get('/coupon', 'index')->name('coupon.index');
    Route::post('/coupon', 'store')->name('coupon.store');
    Route::get('/coupon/edit/{id}', 'edit')->name('coupon.edit');
    Route::put('/coupon/update/{id}', 'update')->name('coupon.update');
    Route::delete('/coupon/delete/{id}', 'destroy')->name('coupon.destroy');
    Route::get('/coupon/status/update/{id}', 'updateStatus')->name('coupon.status.update');
});

//! Route for order management
Route::controller(\App\Http\Controllers\Web\Backend\Order\OrderManagementController::class)->group(function () {
    Route::get('/orders', 'index')->name('orders.index');
    Route::post('/order/status/update/{id}', 'updateStatus')->name('order.status.update');
    Route::get('/order/details/{id}', 'show')->name('order.details');
    Route::delete('/order/delete/{id}', 'destroy')->name('order.delete');
});
