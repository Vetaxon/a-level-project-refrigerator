<?php

namespace App\Http\Controllers\API;

use App\Events\ClientEvent;
use App\Events\Messages\EventMessages;
use App\Http\Controllers\Controller;
use App\Services\Contracts\SearchRecipesContract;
use App\Services\LogEvent;
use App\Services\SearchRecipesWithModel;

class RefrigeratorRecipeController extends Controller
{

    const PAGINATE_RECIPES = 9;


    /**
     * Get all recommended recipes for user according to refrigerator's ingredients.
     *
     * @param SearchRecipesContract|SearchRecipesWithModel $searchRecipes
     * @param LogEvent $event
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function index(SearchRecipesContract $searchRecipes, LogEvent $event)
    {
        $recommendedRecipes = $searchRecipes->searchRecipeForUser(auth()->user());
        
        $event->send(EventMessages::userGetRecommendedRecipes(count($recommendedRecipes)));        

        return response()->json(['data' => $recommendedRecipes]);
    }

}
