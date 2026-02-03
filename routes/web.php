<?php

use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return redirect()->route('search.index');
});

Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/results', [SearchController::class, 'search'])->name('search.results');

Route::get('/hello', function () {
    return view('welcome');
});
