<?php

use Illuminate\Support\Facades\Route;

// ===============================
// AUTH CONTROLLER
// ===============================
use App\Http\Controllers\Api\AuthController;

// ===============================
// MODULE CONTROLLERS
// ===============================
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\BookingController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (TANPA TOKEN)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (WAJIB JWT TOKEN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {

    // ===============================
    // AUTH
    // ===============================
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // ===============================
    // CARS (MOBIL)
    // ===============================
    Route::get('/cars', [CarController::class, 'index']);

    // Admin only
    Route::post('/cars', [CarController::class, 'store']);
    Route::put('/cars/{id}', [CarController::class, 'update']);
    Route::delete('/cars/{id}', [CarController::class, 'destroy']);

    // ===============================
    // BOOKINGS
    // ===============================
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/me', [BookingController::class, 'myBookings']);
});
