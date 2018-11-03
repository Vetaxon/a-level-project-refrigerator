<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 01.11.18
 * Time: 18:41
 */

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class LogRepository
{
    public static function queryLog()
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
}