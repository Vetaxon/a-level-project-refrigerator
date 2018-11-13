<?php

namespace App\Repositories;

use Spatie\Activitylog\Models\Activity;

class EventsRepository
{
    public function getLastEvents()
    {
        return Activity::where('description', 'messages')
            ->orderBy('id', 'desc')
            ->take(20)
            ->get(['properties'])
            ->map(function ($activity) {
                return  [$activity->properties[0]];
            });
    }

}