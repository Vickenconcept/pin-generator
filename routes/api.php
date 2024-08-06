<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;





Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('users', [UserController::class, 'index'])->middleware('auth:sanctum');
// 1|praHBLV8YIyB3KoRLHvAUL6wxr514bojaQvT2oqc9e40b113

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
