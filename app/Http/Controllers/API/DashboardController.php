<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function getEvents()
    {
        $lastActivity = Activity::orderBy('id', 'desc')->take(10)->get();

        return response()->json(['events' => $lastActivity]);
    }
}
