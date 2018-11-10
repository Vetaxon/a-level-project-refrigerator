<?php

namespace App\Providers;

use App\Repositories\LogRepository;
use App\Services\Contracts\PictureContract;
use App\Services\Contracts\SearchRecipesContract;
use App\Services\PictureIntervention;
use App\Services\SearchRecipes;
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
        $this->app->bind(SearchRecipesContract::class, SearchRecipes::class);
    }
}
