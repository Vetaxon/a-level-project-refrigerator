<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function getEvents()
    {
        $activities = collect(Activity::where('description', 'messages')
            ->orderBy('id', 'desc')
            ->take(20)
            ->get(['properties']))
            ->map(function ($activity) {
                return collect([
                    'message' => $activity->properties[0]
                ]);
            })->toArray();
        
        return response()->json(['event' => $activities]);
    }
}
