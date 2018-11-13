<?php

namespace App\Providers;

use App\Repositories\LogRepository;
use App\Services\Contracts\MessageLogEvent;
use App\Services\Contracts\PictureContract;
use App\Services\Contracts\SearchRecipesContract;
use App\Services\LogEvent;
use App\Services\PictureIntervention;
use App\Services\SearchRecipes;
use App\Services\SearchRecipesWithModel;
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
        LogRepository::queryLog();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PictureContract::class, PictureIntervention::class);
        
        $this->app->bind(SearchRecipesContract::class, SearchRecipes::class);
//        $this->app->bind(SearchRecipesContract::class, SearchRecipesWithModel::class);
        
        $this->app->bind(MessageLogEvent::class, LogEvent::class);
    }
}
