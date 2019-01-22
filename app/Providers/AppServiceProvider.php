<?php

namespace App\Providers;

use App\Repository\ComponentRepository;
use App\Repository\DinnerRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ComponentRepository::class);
        $this->app->bind(DinnerRepository::class);
    }
}
