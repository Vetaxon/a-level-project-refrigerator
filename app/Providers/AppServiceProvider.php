<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
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
        DB::listen(function ($query) {

            $query_binding = '';
            foreach ($query->bindings as $binding) {
                $query_binding .= $binding . ', ';
            }

            $log = [
                'sql' => $query->sql,
                'bindings' => $query_binding,
                'time' => $query->time
            ];

            info('sqlstate', $log);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
