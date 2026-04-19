<?php

use App\Http\Controllers\TrekController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\AccountBookingsController;
use App\Http\Controllers\Customer\AccountReviewsController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\EsewaCheckoutController;
use App\Http\Controllers\Admin\DepartureController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TrekController as AdminTrekController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\HotelOwner\HotelController as HotelOwnerHotelController;
use App\Http\Controllers\HotelOwner\DashboardController as HotelOwnerDashboardController;
use App\Http\Controllers\HotelOwner\RoomController as HotelOwnerRoomController;
use App\Http\Controllers\HotelOwner\BookingController as HotelOwnerBookingController;
use App\Http\Controllers\Staff\BookingController as StaffBookingController;
use App\Http\Controllers\Staff\HotelBookingController as StaffHotelBookingController;
use App\Http\Controllers\Staff\PaymentController as StaffPaymentController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\DepartureController as StaffDepartureController;
use App\Http\Controllers\SearchController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');

Route::get('/travel-guide', [HomeController::class, 'travelGuide'])->name('travel-guide');
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');


Route::get('/dashboard', function () {
    return redirect()->route(auth()->user()->dashboardRouteName());
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'settingsProfile'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/security', [ProfileController::class, 'settingsSecurity'])->name('security.show');
    Route::get('/security/password', [ProfileController::class, 'settingsSecurityPassword'])->name('security.password.show');
    Route::patch('/security/password', [ProfileController::class, 'updateSecurityPassword'])->name('security.password');
    Route::post('/avatar', [ProfileController::class, 'storeAvatar'])->name('avatar.store');
    Route::delete('/avatar', [ProfileController::class, 'destroyAvatar'])->name('avatar.destroy');
});

Route::middleware(['auth', 'role:admin,staff,hotel_owner'])->get('/search', SearchController::class)->name('search');

// --- ROLE-BASED DASHBOARDS (THE LOCKED DOORS) ---

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Admin Only
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('treks', AdminTrekController::class);
        Route::resource('departures', DepartureController::class)->except(['destroy']);
        Route::get('trek-bookings', [AdminBookingController::class, 'index'])->name('trek-bookings.index');
        Route::get('trek-bookings/{trekBooking}', [AdminBookingController::class, 'show'])->name('trek-bookings.show');
        Route::patch('trek-bookings/{trekBooking}/status', [AdminBookingController::class, 'updateStatus'])->name('trek-bookings.status');
        Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
        Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::patch('reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
        Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create-staff', [UserController::class, 'createStaff'])->name('users.create-staff');
        Route::post('users/create-staff', [UserController::class, 'storeStaff'])->name('users.store-staff');
        Route::patch('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('hotels', [AdminHotelController::class, 'index'])->name('hotels.index');
        Route::patch('hotels/{hotel}/status', [AdminHotelController::class, 'updateStatus'])->name('hotels.status');


    });

    // Staff Only
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])
        ->middleware('role:staff')
        ->name('staff.dashboard');

    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('trek-bookings', [StaffBookingController::class, 'index'])->name('trek-bookings.index');
        Route::get('trek-bookings/{trekBooking}', [StaffBookingController::class, 'show'])->name('trek-bookings.show');
        Route::patch('trek-bookings/{trekBooking}/status', [StaffBookingController::class, 'updateStatus'])->name('trek-bookings.status');
        Route::get('hotel-bookings', [StaffHotelBookingController::class, 'index'])->name('hotel-bookings.index');
        Route::get('hotel-bookings/{hotelBooking}', [StaffHotelBookingController::class, 'show'])->name('hotel-bookings.show');
        Route::patch('hotel-bookings/{hotelBooking}/status', [StaffHotelBookingController::class, 'updateStatus'])->name('hotel-bookings.status');
        Route::get('payments', [StaffPaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [StaffPaymentController::class, 'show'])->name('payments.show');
        Route::resource('departures', StaffDepartureController::class)->except(['destroy']);
    });

    // Hotel Owner Only
    Route::get('/hotel-owner/dashboard', [HotelOwnerDashboardController::class, 'index'])
        ->middleware('role:hotel_owner')
        ->name('hotel_owner.dashboard');
    Route::middleware('role:hotel_owner')->prefix('hotel-owner')->name('hotel_owner.')->group(function () {
        Route::resource('hotels', HotelOwnerHotelController::class)->except(['show', 'destroy']);
        Route::resource('hotels.rooms', HotelOwnerRoomController::class)->except(['show', 'destroy']);
        Route::get('bookings', [HotelOwnerBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{hotelBooking}', [HotelOwnerBookingController::class, 'show'])->name('bookings.show');
        Route::patch('bookings/{hotelBooking}/status', [HotelOwnerBookingController::class, 'updateStatus'])->name('bookings.status');
    });

    // Customer Only
    Route::get('/customer/dashboard', [DashboardController::class, 'customer'])
        ->middleware('role:customer')
        ->name('customer.dashboard');

    Route::middleware('role:customer')->group(function () {
        Route::post('/hotels/{hotel}/bookings', [BookingController::class, 'storeHotelBooking'])
            ->name('customer.hotel-bookings.store');
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

        Route::get('/payments', [CustomerPaymentController::class, 'index'])->name('payments.index');

        Route::get('/profile', function () {
            return redirect()->route('settings.profile.show');
        })->name('profile.show');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/password', [ProfileController::class, 'updateSecurityPassword'])->name('profile.password');

        Route::post('/reviews/treks/{trek}', [AccountReviewsController::class, 'storeTrek'])->name('reviews.treks.store');
        Route::post('/reviews/hotels/{hotel}', [AccountReviewsController::class, 'storeHotel'])->name('reviews.hotels.store');
        Route::patch('/reviews/{review}', [AccountReviewsController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [AccountReviewsController::class, 'destroy'])->name('reviews.destroy');
    });

});

// --- STANDARD ROUTES ---

Route::get('/treks', [TrekController::class, 'index'])->name('treks.index');
Route::get('/treks/{trek}', [TrekController::class, 'show'])->name('treks.show');

Route::middleware('auth')->group(function () {
    Route::get('/bookings/create/{departure}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/passengers', [BookingController::class, 'passengers'])->name('bookings.passengers');
    Route::post('/bookings/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::get('/payments/stripe/{payment}/checkout', [StripeCheckoutController::class, 'retry'])->name('stripe.retry');
    Route::get('/payments/stripe/{payment}/success', [StripeCheckoutController::class, 'success'])->name('stripe.success');
    Route::get('/payments/stripe/{payment}/cancel', [StripeCheckoutController::class, 'cancel'])->name('stripe.cancel');
    Route::get('/payments/esewa/{payment}/checkout', [EsewaCheckoutController::class, 'retry'])->name('esewa.retry');
    Route::get('/payments/esewa/{payment}/success', [EsewaCheckoutController::class, 'success'])->name('esewa.success');
    Route::get('/payments/esewa/{payment}/failure', [EsewaCheckoutController::class, 'failure'])->name('esewa.failure');

    Route::get('/profile', function () {
        return redirect()->route('settings.profile.show');
    })->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/payments/stripe/webhook', [StripeCheckoutController::class, 'webhook'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('stripe.webhook');

require __DIR__.'/auth.php';
