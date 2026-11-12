<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminFieldController;
use App\Http\Controllers\AdminRacketController;

/*
|--------------------------------------------------------------------------
| Web Routes - Publik (Premium Padel)
|--------------------------------------------------------------------------
*/
// 1. Halaman Utama (Landing Page)
Route::get('/', [BookingController::class, 'home'])->name('home');

// 2. Halaman Booking Lapangan, Raket & Bola
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
Route::get('/api/booked-slots', [BookingController::class, 'getBookedSlots'])->name('booking.slots');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// 3. Proses Kirim Ulasan
Route::post('/review', [BookingController::class, 'storeReview'])->name('review.store');

/*
|--------------------------------------------------------------------------
| Web Routes - Autentikasi & Panel Admin (Terpisah & Tersembunyi)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminBookingController::class, 'index'])->name('dashboard');
        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update');

        Route::get('/fields', [AdminFieldController::class, 'index'])->name('fields.index');
        Route::post('/fields', [AdminFieldController::class, 'store'])->name('fields.store');
        Route::put('/fields/{field}', [AdminFieldController::class, 'update'])->name('fields.update');
        Route::delete('/fields/{field}', [AdminFieldController::class, 'destroy'])->name('fields.destroy');

        Route::get('/rackets', [AdminRacketController::class, 'index'])->name('rackets.index');
        Route::post('/rackets', [AdminRacketController::class, 'store'])->name('rackets.store');
        Route::put('/rackets/{racket}', [AdminRacketController::class, 'update'])->name('rackets.update');
        Route::delete('/rackets/{racket}', [AdminRacketController::class, 'destroy'])->name('rackets.destroy');
    });
});
