<?php

use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Inertia::render('Homepage/Index');
})
    ->name('home');

Route::get('/test', function () {
    return Inertia::render('Quest/Create');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'loginIndex')->name('login');
    Route::get('/register', 'registerIndex')->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/register', 'register')->name('auth.register');
    Route::get('/oauth/login-github', 'loginGithub')->name('auth.login-github');
    Route::get('/oauth/callback', 'handleOAuthLogin')->name('auth.callback');
});

Route::controller(ProfileController::class)->middleware('auth')->group(function () {
    Route::get('/profile', 'index')->name('profile.index');
    Route::post('/profile/update', 'update')->name('profile.update');
});
