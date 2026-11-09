<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminFieldController;
use App\Http\Controllers\AdminRacketController;

/*
|--------------------------------------------------------------------------
| Web Routes - Publik (Pengguna / Pelanggan)
|--------------------------------------------------------------------------
*/
// Halaman utama pemesanan lapangan & ulasan
Route::get('/', [BookingController::class, 'index'])->name('booking.index');

// API cek ketersediaan slot jam
Route::get('/api/booked-slots', [BookingController::class, 'getBookedSlots'])->name('booking.slots');

// Proses simpan reservasi
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Proses kirim ulasan & rating bintang (Rute yang sebelumnya belum ada)
Route::post('/review', [BookingController::class, 'storeReview'])->name('review.store');

// Halaman status pembayaran
Route::get('/booking/success', [BookingController::class, 'success'])->name('booking.success');
Route::get('/booking/pending', [BookingController::class, 'pending'])->name('booking.pending');

/*
|--------------------------------------------------------------------------
| Web Routes - Autentikasi & Panel Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Login Admin
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/rackets', [AdminRacketController::class, 'index'])->name('rackets.index');
    Route::post('/rackets', [AdminRacketController::class, 'store'])->name('rackets.store');
    Route::put('/rackets/{racket}', [AdminRacketController::class, 'update'])->name('rackets.update');
    Route::delete('/rackets/{racket}', [AdminRacketController::class, 'destroy'])->name('rackets.destroy');

    // Area Khusus Admin (Wajib Login)
    Route::middleware('auth')->group(function () {
        // Dashboard & Konfirmasi Pesanan
        Route::get('/dashboard', [AdminBookingController::class, 'index'])->name('dashboard');
        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update');

        // Manajemen Lapangan (CRUD)
        Route::get('/fields', [AdminFieldController::class, 'index'])->name('fields.index');
        Route::post('/fields', [AdminFieldController::class, 'store'])->name('fields.store');
        Route::put('/fields/{field}', [AdminFieldController::class, 'update'])->name('fields.update');
        Route::delete('/fields/{field}', [AdminFieldController::class, 'destroy'])->name('fields.destroy');
    });
});
