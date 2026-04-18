<?php

namespace App\Providers;

use App\Models\Departure;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\HotelRoom;
use App\Models\Itinerary;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Trek;
use App\Models\TrekBooking;
use App\Models\User;
use App\Policies\DeparturePolicy;
use App\Policies\HotelBookingPolicy;
use App\Policies\HotelPolicy;
use App\Policies\HotelRoomPolicy;
use App\Policies\ItineraryPolicy;
use App\Policies\PassengerPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\SettingsPolicy;
use App\Policies\TrekBookingPolicy;
use App\Policies\TrekPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Intentionally left minimal in Pass B; business services use Eloquent directly.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enforce global password requirements
        \Illuminate\Validation\Rules\Password::defaults(function () {
            return \Illuminate\Validation\Rules\Password::min(8);
        });

        // Register authorization policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Trek::class, TrekPolicy::class);
        Gate::policy(Departure::class, DeparturePolicy::class);
        Gate::policy(TrekBooking::class, TrekBookingPolicy::class);
        Gate::policy(Itinerary::class, ItineraryPolicy::class);
        Gate::policy(Passenger::class, PassengerPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(Hotel::class, HotelPolicy::class);
        Gate::policy(HotelRoom::class, HotelRoomPolicy::class);
        Gate::policy(HotelBooking::class, HotelBookingPolicy::class);

        Gate::policy(Review::class, ReviewPolicy::class);

        // Define custom gates for non-model policies
        Gate::define('access-settings', function (User $user) {
            return (new SettingsPolicy())->update($user);
        });

        Gate::define('access-payment-gateway', function (User $user) {
            return (new SettingsPolicy())->accessPaymentGateway($user);
        });

        Gate::define('access-dashboard', function (User $user) {
            return (new SettingsPolicy())->accessDashboard($user);
        });

        Gate::define('access-reports', function (User $user) {
            return (new SettingsPolicy())->accessReports($user);
        });

        // Register Blade authorization directives
        $this->registerBladeDirectives();
    }

    /**
     * Register custom Blade directives for authorization checks
     */
    private function registerBladeDirectives(): void
    {
        // @isAdmin / @endisAdmin
        Blade::if('isAdmin', function () {
            return auth()->check() && auth()->user()->role === 'admin';
        });

        // @isStaff / @endisStaff
        Blade::if('isStaff', function () {
            return auth()->check() && auth()->user()->role === 'staff';
        });

        // @isOperational / @endisOperational (Admin or Staff)
        Blade::if('isOperational', function () {
            return auth()->check() && in_array(auth()->user()->role, ['admin', 'staff']);
        });

        // @isCustomer / @endisCustomer
        Blade::if('isCustomer', function () {
            return auth()->check() && auth()->user()->role === 'customer';
        });

        // @isHotelOwner / @endisHotelOwner
        Blade::if('isHotelOwner', function () {
            return auth()->check() && auth()->user()->role === 'hotel_owner';
        });
    }
}

