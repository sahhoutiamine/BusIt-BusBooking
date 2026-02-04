<?php

use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return redirect()->route('search.index');
});

Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/results', [SearchController::class, 'search'])->name('search.results');

use App\Http\Controllers\BookingController;
Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/confirmation/{reservation}', [BookingController::class, 'confirmation'])->name('booking.confirmation');

Route::get('/hello', function () {
    return view('welcome');
});
