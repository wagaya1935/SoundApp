<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoundController;

Route::get('/', [SoundController::class, 'index'])->name('sounds.index');

Route::get('/sounds/create', [SoundController::class, 'create'])->name('sounds.create');

Route::post('/sounds', [SoundController::class, 'store'])->name('sounds.store');

Route::get('/sounds/{id}', [SoundController::class, 'destroy'])->name('sounds.destroy');
