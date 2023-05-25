<?php

namespace App\Providers;

use App\Support\Docker;
use App\Support\Filesystem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Docker::class);
        $this->app->singleton(Filesystem::class);
    }
}
