<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function modelLogs()
    {
        $activities = collect(Activity::where('description', 'messages')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get(['id', 'description', 'properties', 'created_at']))
            ->map(function ($value) {
                return collect($value)
                    ->map(function($value){
                        if(is_object($value)){
                            return collect($value)->first();
                        }
                        return $value;
                    });
            })->toArray();

        dump($activities);die();
    }
}
