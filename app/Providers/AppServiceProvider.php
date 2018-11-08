<?php

namespace App\Providers;

use App\Repositories\LogRepository;
use App\Services\Contracts\PictureContract;
use App\Services\PictureIntervention;
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
//        LogRepository::queryLog();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        info([SavePictureContract::class, SavePictureIntervention::class]);
        $this->app->bind(PictureContract::class, PictureIntervention::class);
    }
}
