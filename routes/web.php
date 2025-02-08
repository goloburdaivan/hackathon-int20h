<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\Room\DiceController;
use App\Http\Controllers\Room\RoomController;
use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Homepage/Index');
})
    ->name('home');

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'loginIndex')
        ->name('login');
    Route::get('/register', 'registerIndex')
        ->name('register');
    Route::post('/logout', 'logout')
        ->name('logout');
    Route::post('/login', 'login')
        ->name('auth.login');
    Route::post('/register', 'register')
        ->name('auth.register');
    Route::get('/oauth/login-github', 'loginGithub')
        ->name('auth.login-github');
    Route::get('/oauth/callback', 'handleOAuthLogin')
        ->name('auth.callback');
});

Route::controller(ProfileController::class)->middleware('auth')->group(function () {
    Route::get('/profile', 'index')
        ->name('profile.index');
    Route::post('/profile/update', 'update')
        ->name('profile.update');
});

Route::controller(QuestController::class)->middleware('auth')->group(function () {
    Route::get('/quests', 'index')
        ->name('quest.index');
    Route::get('/user-quests', 'getUserQuests')
        ->name('quest.user-quests');
    Route::get('/quests/create', 'create')
        ->name('quest.create');
    Route::post('/quests', 'store')
        ->name('quest.store');
    Route::get('/quests/{quest}', 'show')
        ->name('quest.show');
    Route::get('/quests/{quest}/edit', 'edit')
        ->name('quest.edit');
    Route::patch('/quests/{quest}', 'update')
        ->name('quest.update');
    Route::delete('/quests/{quest}', 'destroy')
        ->name('quest.destroy');
    Route::post('/quests/{quest}/start', 'startQuest')
        ->name('quest.start');
});

Route::controller(QuestionController::class)->middleware('auth')->group(function () {
    Route::get('/quests/{quest}/questions/{question}', 'show');
    Route::post('/quests/{quest}/questions', 'store');
    Route::delete('/quests/{quest}/questions/{question}', 'destroy');
    Route::patch('/quests/{quest}/questions/{question}', 'update');
});

Route::controller(RoomController::class)->middleware('auth')->group(function () {
    Route::get('/rooms/{room}', 'index');
});

Route::post('/rooms/{room}/roll-dice', [DiceController::class, 'rollDice']);
