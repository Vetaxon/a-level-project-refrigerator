<?php

namespace App\Providers;

use App\Contracts\SavePictureContract;
use App\Services\SavePictureIntervention;
use Illuminate\Support\ServiceProvider;

class CustomServicesProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(SavePictureContract::class, function ($app) {
            return new SavePictureIntervention();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SavePictureContract::class, function ($app) {
            return new SavePictureIntervention();
        });
    }
}
