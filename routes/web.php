<?php

use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Homepage/Index');
})
    ->name('home')
    ->middleware(['auth']);

Route::get('/test', function () {
    return Inertia::render('Quest/Create');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'loginIndex')->name('login');
    Route::get('/register', 'registerIndex')->name('register');
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/register', 'register')->name('auth.register');
    Route::get('/oauth/login-github', 'loginGithub')->name('auth.login-github');
    Route::get('/oauth/callback', 'handleOAuthLogin')->name('auth.callback');
});
