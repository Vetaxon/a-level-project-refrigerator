<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Measure;
use App\Http\Controllers\Controller;

class MeasureController extends Controller
{
    /**
     * Display all Measures.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json((new Measure())->all(['id', 'name']));
    }
}
