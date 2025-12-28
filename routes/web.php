<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
| Digunakan untuk tampilan (Blade / Frontend)
|--------------------------------------------------------------------------
*/

// ===============================
// LANDING PAGE
// ===============================
Route::get('/', function () {
    return view('welcome');
});

// ===============================
// AUTH (VIEW ONLY)
// ===============================
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// ===============================
// LOGOUT (OPSIONAL - VIEW)
// ===============================
Route::get('/logout', function () {
    // Biasanya logout via API (JWT)
    return redirect('/login');
})->name('logout');

// ===============================
// Hlaman Web
// ===============================

// Halaman daftar mobil
Route::get('/cars', function () {
    return view('cars.index');
})->name('cars.index');

// Halaman booking saya
Route::get('/bookings/my', function () {
    return view('bookings.my');
})->name('bookings.my');

// Jika ingin halaman utama diarahkan ke daftar mobil
Route::get('/', function () {
    return redirect()->route('cars.index');
});