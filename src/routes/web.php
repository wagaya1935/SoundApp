<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoundController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;

Route::get('/', [SoundController::class, 'index'])->name('sounds.index');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');

    Route::get('/profile/delete', [UserController::class, 'confirmDelete'])->name('profile.delete.confirm');

    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');

    Route::get('/sounds/create', [SoundController::class, 'create'])->name('sounds.create');

    Route::post('/sounds', [SoundController::class, 'store'])->name('sounds.store');

    Route::post('/sounds/{id}/like', [SoundController::class, 'toggleLike'])->name('sounds.like');

    Route::post('/sounds/{sound}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::delete('/sounds/{sound}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::delete('/sounds/{id}', [SoundController::class, 'destroy'])->name('sounds.destroy');
});

Route::get('/sounds/{sound}', [SoundController::class, 'show'])->name('sounds.show');
