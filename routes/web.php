<?php

use App\Http\Controllers\TrekController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Account\BookingsController as AccountBookingsController;
use App\Http\Controllers\Account\PaymentsController as AccountPaymentsController;
use App\Http\Controllers\Account\ProfileController as AccountProfileController;
use App\Http\Controllers\Account\ReviewsController as AccountReviewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDepartureController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminTrekController;
use App\Http\Controllers\Admin\AdminTrekBookingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminHotelController;
use App\Http\Controllers\HotelOwner\HotelController as HotelOwnerHotelController;
use App\Http\Controllers\Staff\StaffTrekBookingController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');


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

    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('trek-bookings', [StaffTrekBookingController::class, 'index'])->name('trek-bookings.index');
        Route::get('trek-bookings/{trekBooking}', [StaffTrekBookingController::class, 'show'])->name('trek-bookings.show');
        Route::patch('trek-bookings/{trekBooking}/status', [StaffTrekBookingController::class, 'updateStatus'])->name('trek-bookings.status');
    });

    // Hotel Owner Only
    Route::get('/hotel-owner/dashboard', [DashboardController::class, 'hotelOwner'])
        ->middleware('role:hotel_owner')
        ->name('hotel_owner.dashboard');
    Route::middleware('role:hotel_owner')->prefix('hotel-owner')->name('hotel_owner.')->group(function () {
        Route::resource('hotels', HotelOwnerHotelController::class)->except(['show', 'destroy']);
    });

    // Customer Only
    Route::get('/customer/dashboard', [DashboardController::class, 'customer'])
        ->middleware('role:customer')
        ->name('customer.dashboard');

    Route::middleware('role:customer')->group(function () {
        Route::get('/customer/trek-bookings/{trekBooking}', [BookingController::class, 'showTrekBooking'])
            ->name('customer.trek-bookings.show');
        Route::get('/customer/hotel-bookings/{hotelBooking}', [BookingController::class, 'showHotelBooking'])
            ->name('customer.hotel-bookings.show');
    });

    Route::middleware('role:customer')->prefix('account')->name('account.')->group(function () {
        Route::get('/bookings', [AccountBookingsController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/treks/{trekBooking}', [AccountBookingsController::class, 'showTrek'])->name('bookings.treks.show');
        Route::patch('/bookings/treks/{trekBooking}/passengers', [AccountBookingsController::class, 'updatePassengers'])->name('bookings.treks.passengers');
        Route::get('/bookings/hotels/{hotelBooking}', [AccountBookingsController::class, 'showHotel'])->name('bookings.hotels.show');
        Route::patch('/bookings/treks/{trekBooking}/cancel', [AccountBookingsController::class, 'cancelTrek'])->name('bookings.treks.cancel');
        Route::patch('/bookings/treks/{trekBooking}/cancel-withdraw', [AccountBookingsController::class, 'withdrawTrekCancellation'])->name('bookings.treks.cancel-withdraw');
        Route::patch('/bookings/hotels/{hotelBooking}/cancel', [AccountBookingsController::class, 'cancelHotel'])->name('bookings.hotels.cancel');
        Route::patch('/bookings/hotels/{hotelBooking}/cancel-withdraw', [AccountBookingsController::class, 'withdrawHotelCancellation'])->name('bookings.hotels.cancel-withdraw');
        Route::get('/bookings/treks/{trekBooking}/receipt', [AccountBookingsController::class, 'trekReceipt'])->name('bookings.treks.receipt');
        Route::get('/bookings/hotels/{hotelBooking}/receipt', [AccountBookingsController::class, 'hotelReceipt'])->name('bookings.hotels.receipt');

        Route::get('/payments', [AccountPaymentsController::class, 'index'])->name('payments.index');

        Route::get('/profile', [AccountProfileController::class, 'show'])->name('profile.show');
        Route::patch('/profile', [AccountProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/password', [AccountProfileController::class, 'updatePassword'])->name('profile.password');

        Route::post('/reviews/treks/{trek}', [AccountReviewsController::class, 'storeTrek'])->name('reviews.treks.store');
        Route::post('/reviews/hotels/{hotel}', [AccountReviewsController::class, 'storeHotel'])->name('reviews.hotels.store');
        Route::patch('/reviews/{review}', [AccountReviewsController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [AccountReviewsController::class, 'destroy'])->name('reviews.destroy');
    });

});

// --- STANDARD ROUTES ---

Route::get('/treks', [TrekController::class, 'index'])->name('treks.index');
Route::get('/treks/{slug}', [TrekController::class, 'show'])->name('treks.show');

Route::middleware('auth')->group(function () {
    Route::get('/bookings/create/{departure}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/passengers', [BookingController::class, 'passengers'])->name('bookings.passengers');
    Route::post('/bookings/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::get('/payments/stripe/{payment}/checkout', [StripeCheckoutController::class, 'retry'])->name('stripe.retry');
    Route::get('/payments/stripe/{payment}/success', [StripeCheckoutController::class, 'success'])->name('stripe.success');
    Route::get('/payments/stripe/{payment}/cancel', [StripeCheckoutController::class, 'cancel'])->name('stripe.cancel');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/payments/stripe/webhook', [StripeCheckoutController::class, 'webhook'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('stripe.webhook');

require __DIR__.'/auth.php';
