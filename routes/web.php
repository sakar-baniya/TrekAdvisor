<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrekController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDepartureController;
use App\Http\Controllers\Admin\AdminGearController;
use App\Http\Controllers\Admin\AdminGearRentalController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminReviewController;
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
        Route::resource('gear', AdminGearController::class)->except(['show', 'destroy']);
        Route::get('gear-rentals', [AdminGearRentalController::class, 'index'])->name('gear-rentals.index');
        Route::patch('gear-rentals/{gearRental}/return', [AdminGearRentalController::class, 'markReturned'])->name('gear-rentals.return');
        Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/flagged', [AdminReviewController::class, 'flagged'])->name('reviews.flagged');
        Route::get('reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
        Route::patch('reviews/{review}/flag', [AdminReviewController::class, 'toggleFlag'])->name('reviews.flag');
        Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('users/create-staff', [AdminUserController::class, 'createStaff'])->name('users.create-staff');
        Route::post('users/create-staff', [AdminUserController::class, 'storeStaff'])->name('users.store-staff');
        Route::patch('users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
        Route::patch('users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');

        Route::get('hotels', [AdminHotelController::class, 'index'])->name('hotels.index');
        Route::patch('hotels/{hotel}/status', [AdminHotelController::class, 'updateStatus'])->name('hotels.status');
    });

    // Staff Only
    Route::get('/staff/dashboard', [DashboardController::class, 'staff'])
        ->middleware('role:staff')
        ->name('staff.dashboard');

    // Hotel Owner Only
    Route::get('/hotel-owner/dashboard', [DashboardController::class, 'hotelOwner'])
        ->middleware('role:hotel_owner')
        ->name('hotel_owner.dashboard');

    // Customer Only
    Route::get('/customer/dashboard', [DashboardController::class, 'customer'])
        ->middleware('role:customer')
        ->name('customer.dashboard');

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
