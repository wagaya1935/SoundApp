<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoundController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sounds/create', [SoundController::class, 'create']); 
Route::post('/sounds', [SoundController::class, 'store']);
