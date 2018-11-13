<?php

namespace App\Providers;

use App\Repositories\EventsRepository;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param EventsRepository $eventsRepository
     * @return void
     */
    public function boot(EventsRepository $eventsRepository)
    {
        view()->composer('home', function ($view) use ($eventsRepository) {
            $view->withEvents($eventsRepository->getLastEvents()->toJson());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
