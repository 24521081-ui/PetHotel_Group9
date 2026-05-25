<?php

namespace App\Providers;

use App\Models\Pet;
use App\Policies\PetPolicy;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Eloquent\BookingRepository;
use App\Repositories\Eloquent\PaymentRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Ràng buộc Interface với Eloquent Implementation
        $this->app->bind(
            BookingRepositoryInterface::class,
            BookingRepository::class
        );

        $this->app->bind(
            PaymentRepositoryInterface::class,
            PaymentRepository::class
        );
    }

    public function boot(): void
    {
        Gate::policy(Pet::class, PetPolicy::class);
    }
}
