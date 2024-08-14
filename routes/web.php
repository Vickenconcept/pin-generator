<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::middleware('guest')->group(function () {
    Route::view('login', 'auth.login')->name('login');
    Route::get('forgot-password', [PasswordResetController::class, 'index'])->name('password.request');
    Route::post('forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');

    Route::controller(AuthController::class)->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('auth/register', 'register')->name('auth.register');
        Route::post('auth/login', 'login')->name('auth.login');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('templates', TemplateController::class);
    Route::get('templates-builder', [TemplateController::class, 'template_build'])->name('template.build');
    Route::post('upload-video', [TemplateController::class, 'uploadVideo'])->name('uploadVideo');
    
    Route::get('pins/generate', [PinController::class, 'generate'])->name('pins.generate');
    Route::post('pins/generate-random', [PinController::class, 'generateRandomPins'])->name('pins.generateRandom');
    Route::resource('pins', PinController::class);


    Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
