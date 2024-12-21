<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\LocationController;
use App\Http\Controllers\Web\Backend\SettingController;
use App\Http\Controllers\Web\Backend\SocialMediaController;
use App\Http\Controllers\Web\Backend\UserUpdateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



//! Route for Reset Database and Optimize Clear
Route::get('/reset', [ResetController::class, 'RunMigrations'])->name('reset');
Route::get('/clear/cache', [ResetController::class, 'cacheClear'])->name('cache.clear');
Route::get('/dump/autoload', [ResetController::class, 'dumpAutoLoad'])->name('dump.autoload');

Route::get('/comand/{comand}', [ResetController::class, 'comand'])->name('comand');
Route::get('/composer/update', [ResetController::class, 'composerUpdate'])->name('composer.update');





require __DIR__ . '/auth.php';
