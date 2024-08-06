<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PinController;
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

    Route::get('pins/create', [PinController::class, 'create'])->name('pins.create');
    Route::post('pins', [PinController::class, 'store'])->name('pins.store');
    Route::get('pins/{pin}/generate', [PinController::class, 'generatePinImage'])->name('pins.generate');


    Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
});