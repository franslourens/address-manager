<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use App\Services\Geocoding\GeocodeServiceInterface;
use App\Services\Geocoding\NeutrinoGeocodeService;
use App\Models\Address;
use App\Observers\AddressObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GeocodeServiceInterface::class, function ($app) {
            return new NeutrinoGeocodeService(
                userId: config('services.neutrino.user_id'),
                apiKey: config('services.neutrino.api_key'),
            );
    }   );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Address::observe(AddressObserver::class);
    }
}
