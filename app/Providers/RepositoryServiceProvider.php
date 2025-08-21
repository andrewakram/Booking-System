<?php

namespace App\Providers;

use App\Interfaces\BookingRepositoryInterface;
use App\Interfaces\BookingStatusRepositoryInterface;
use App\Interfaces\ServiceRepositoryInterface;
use App\Interfaces\LocationRepositoryInterface;

use App\Repositories\BookingRepository;
use App\Repositories\BookingStatusRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\LocationRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(BookingStatusRepositoryInterface::class, BookingStatusRepository::class);
    }
}
