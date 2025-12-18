<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoundController;
use App\Http\Controllers\AuthController;

Route::get('/', [SoundController::class, 'index'])->name('sounds.index');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/sounds/create', [SoundController::class, 'create'])->name('sounds.create');
    
    Route::post('/sounds', [SoundController::class, 'store'])->name('sounds.store');
    
    Route::get('/sounds/{id}', [SoundController::class, 'destroy'])->name('sounds.destroy');

});
