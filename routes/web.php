<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return redirect()->route('search.index');
});

Route::get('/search', [SearchController::class, 'index'])->name('search.index');

Route::middleware('auth')->group(function () {
    Route::get('/my-reservations', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/search/results', [SearchController::class, 'search'])->name('search.results');
    Route::get('/booking/create/{segment}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/confirmation/{reservation}', [BookingController::class, 'confirmation'])->name('booking.confirmation');
    Route::get('/booking/ticket/{reservation}', [BookingController::class, 'ticket'])->name('booking.ticket');

    Route::get('/dashboard', [BookingController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
