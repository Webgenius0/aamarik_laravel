<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\DuaCategoryController;
use App\Http\Controllers\Web\Backend\DuaController;
use App\Http\Controllers\Web\Backend\MessagingController;
use App\Http\Controllers\Web\Backend\NewsLetterController;
use App\Http\Controllers\Web\Backend\SettingController;
use App\Http\Controllers\Web\Backend\SocialMediaController;
use App\Http\Controllers\Web\Backend\UserUpdateController;
use App\Http\Controllers\Web\Backend\VerseController;
use App\Models\DuaCategory;
use Illuminate\Support\Facades\Route;

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


// Verse
Route::controller(VerseController::class)->group(function () {
    Route::get('verse', 'index')->name('verse.index');
    Route::get('verse/create', 'create')->name('verse.create');
    Route::post('verse/store', 'store')->name('verse.store');
    Route::get('verse/edit/{id}', 'edit')->name('verse.edit');
    Route::post('verse/update/{id}', 'update')->name('verse.update');
    Route::delete('verse/{id}', 'destroy')->name('verse.destroy');
    Route::get('verse/status/{id}', 'status')->name('verse.status');
});

Route::controller(DuaCategoryController::class)->group(function () {
    Route::get( 'dua/category', 'index' )->name( 'dua.category.index' );
    Route::get( 'dua/category/create', 'create' )->name( 'dua.category.create' );
    Route::post( 'dua/category/store', 'store' )->name( 'dua.category.store' );
    Route::get( 'dua/category/edit/{id}', 'edit' )->name( 'dua.category.edit' );
    Route::post( 'dua/category/update/{id}', 'update' )->name( 'dua.category.update' );
    Route::delete( 'dua/category/{id}', 'destroy' )->name( 'dua.category.destroy' );
    Route::get( 'dua/category/status/{id}', 'status' )->name( 'dua.category.status' );
});
Route::post('/sub_category', [DuaCategoryController::class, 'Sub_category'])->name('sub_category.store');
Route::get('/categories', [DuaCategoryController::class, 'fetchCategories'])->name('fetch.categories');
Route::delete('/subcategory/delete', [DuaCategoryController::class, 'deleteSubcategory'])->name('subcategory.delete');





Route::controller(DuaController::class)->group(function () {
    Route::get( 'dua', 'index' )->name( 'dua.index' );
    Route::get( 'dua/create', 'create' )->name( 'dua.create' );
    Route::post( 'dua/store', 'store' )->name( 'dua.store' );
    Route::get( 'dua/edit/{id}', 'edit' )->name( 'dua.edit' );
    Route::post( 'dua/update/{id}', 'update' )->name( 'dua.update' );
    Route::delete( 'dua/{id}', 'destroy' )->name( 'dua.destroy' );
    Route::get( 'dua/status/{id}', 'status' )->name( 'dua.status' );
});
Route::get('categories/{categoryId}/subcategories', [DuaController::class, 'getSubcategories'])->name('categories.subcategories');





Route::controller(MessagingController::class)->group(function () {
    Route::get( 'messages', 'index' )->name( 'message.index' );
    Route::get( 'chat/{chat_id}', 'chat' )->name( 'chat' );
    Route::post( 'chat', 'store' )->name( 'chat.store' );
});


Route::controller(NewsLetterController::class)->group(function () {
    Route::get( 'news/letter', 'index' )->name( 'news.letter.index' );
    Route::get( 'news/letter/create', 'create' )->name( 'news.letter.create' );
    Route::post( 'news/letter/store', 'store' )->name( 'news.letter.store' );
    Route::get( 'news/letter/edit/{id}', 'edit' )->name( 'news.letter.edit' );
    Route::post( 'news/letter/update/{id}', 'update' )->name( 'news.letter.update' );
    Route::delete( 'news/letter/{id}', 'destroy' )->name( 'news.letter.destroy' );
    Route::get( 'news/letter/status/{id}', 'status' )->name( 'news.letter.status' );
});

