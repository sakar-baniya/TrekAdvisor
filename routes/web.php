<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrekController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDepartureController;
use App\Http\Controllers\Admin\AdminTrekController;
use App\Http\Controllers\Admin\AdminTrekBookingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminHotelController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return redirect()->route(auth()->user()->dashboardRouteName());
})->middleware(['auth', 'verified'])->name('dashboard');

// --- ROLE-BASED DASHBOARDS (THE LOCKED DOORS) ---

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Admin Only
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('treks', AdminTrekController::class);
        Route::resource('departures', AdminDepartureController::class)->except(['destroy']);
        Route::get('trek-bookings', [AdminTrekBookingController::class, 'index'])->name('trek-bookings.index');
        Route::get('trek-bookings/{trekBooking}', [AdminTrekBookingController::class, 'show'])->name('trek-bookings.show');
        Route::patch('trek-bookings/{trekBooking}/status', [AdminTrekBookingController::class, 'updateStatus'])->name('trek-bookings.status');
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('users/create-staff', [AdminUserController::class, 'createStaff'])->name('users.create-staff');
        Route::post('users/create-staff', [AdminUserController::class, 'storeStaff'])->name('users.store-staff');
        Route::patch('users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
        Route::patch('users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');

        Route::get('hotels', [AdminHotelController::class, 'index'])->name('hotels.index');
        Route::patch('hotels/{hotel}/status', [AdminHotelController::class, 'updateStatus'])->name('hotels.status');
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
