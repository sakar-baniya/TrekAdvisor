<?php

namespace App\Providers;

use App\Repositories\Contracts\DepartureRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\TrekBookingRepositoryInterface;
use App\Repositories\Eloquent\EloquentDepartureRepository;
use App\Repositories\Eloquent\EloquentPaymentRepository;
use App\Repositories\Eloquent\EloquentTrekBookingRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DepartureRepositoryInterface::class, EloquentDepartureRepository::class);
        $this->app->bind(TrekBookingRepositoryInterface::class, EloquentTrekBookingRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, EloquentPaymentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
