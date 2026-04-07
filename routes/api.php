<?php

use App\Http\Controllers\Api\V1\TrekController;
use App\Http\Controllers\Api\V1\HotelController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/treks', [TrekController::class, 'index'])->name('api.v1.treks.index');
    Route::get('/hotels', [HotelController::class, 'index'])->name('api.v1.hotels.index');
});