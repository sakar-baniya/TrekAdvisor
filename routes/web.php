<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrekController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return redirect()->route(auth()->user()->dashboardRouteName());
})->middleware(['auth', 'verified'])->name('dashboard');

// --- ROLE-BASED DASHBOARDS (THE LOCKED DOORS) ---

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Admin Only
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('role:admin')->name('admin.dashboard');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('treks', \App\Http\Controllers\Admin\AdminTrekController::class);
    });

    // Staff Only
    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->middleware('role:staff')->name('staff.dashboard');

    // Hotel Owner Only
    Route::get('/hotel-owner/dashboard', function () {
        return view('hotel.dashboard');
    })->middleware('role:hotel_owner')->name('hotel_owner.dashboard');

    // Customer Only
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->middleware('role:customer')->name('customer.dashboard');

});

// --- STANDARD ROUTES ---

Route::get('/treks', [TrekController::class, 'index'])->name('treks.index');
Route::get('/treks/{slug}', [TrekController::class, 'show'])->name('treks.show');

Route::middleware('auth')->group(function () {
    Route::get('/bookings/create/{departure}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/passengers', [BookingController::class, 'passengers'])->name('bookings.passengers');
    Route::post('/bookings/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
