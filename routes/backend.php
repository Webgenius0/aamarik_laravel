<?php

use App\Models\DuaCategory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Backend\DuaController;
use App\Http\Controllers\Web\Backend\VerseController;
use App\Http\Controllers\Web\Backend\SettingController;
use App\Http\Controllers\Web\Backend\LocationController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\MessagingController;
use App\Http\Controllers\Web\Backend\NewsLetterController;
use App\Http\Controllers\Web\Backend\UserUpdateController;
use App\Http\Controllers\Web\Backend\DuaCategoryController;
use App\Http\Controllers\Web\Backend\SocialMediaController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Edit Profile
Route::get('profile', [DashboardController::class, 'editProfile'])->name('admin.edit.profile');
Route::post('edit-profile', [UserUpdateController::class, 'update'])->name('admin.edit.profile.update');
Route::post('change-password', [UserUpdateController::class, 'userPasswordUpdate'])->name('admin.change.password');

// Setting
Route::post('setting-update', [SettingController::class, 'update'])->name('admin.setting.update');
Route::get('setting', [SettingController::class, 'index'])->name('admin.setting');

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

Route::controller(LocationController::class)->group(function () {
    
    Route::get('/locations', 'index')->name('location.index');
    Route::get('/locations/create', 'create')->name('location.create');
    Route::post('/locations', 'store')->name('location.store');
    Route::get('/locations/edit/{id}', 'edit')->name('location.edit');
    Route::put('/locations/update/{id}', 'update')->name('location.update');
    Route::get('/locations/status/{id}', 'status')->name('location.status');
    Route::delete('/locations/{id}', 'destroy')->name('location.destroy');
    
})->middleware('auth,admin');