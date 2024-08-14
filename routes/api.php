<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;





Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('users', [UserController::class, 'index'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('pins/generate-random', [PinController::class, 'generateRandomPins']);
    Route::resource('pins', PinController::class);

    Route::resource('templates', TemplateController::class);
    Route::get('templates-builder', [TemplateController::class, 'template_build']);
});

