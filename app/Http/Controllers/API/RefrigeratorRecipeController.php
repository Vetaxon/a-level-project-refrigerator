<?php

namespace App\Http\Controllers\API;

use App\Events\ClientEvent;
use App\Events\Messages\EventMessages;
use App\Http\Controllers\Controller;
use App\Services\Contracts\SearchRecipesContract;
use App\Services\SearchRecipesWithModel;

class RefrigeratorRecipeController extends Controller
{

    const PAGINATE_RECIPES = 9;


    /**
     * Get all recommended recipes for user according to refrigerator's ingredients.
     *
     * @param SearchRecipesContract|SearchRecipesWithModel $searchRecipes
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function index(SearchRecipesContract $searchRecipes)
    {
        $recommendedRecipes = $searchRecipes->searchRecipeForUser(auth()->user());

        $message = EventMessages::userGetRecommendedRecipes(count($recommendedRecipes));
        
        activity()->withProperties($message)->log('messages');
        
        ClientEvent::dispatch($message);

        return response()->json(['data' => $recommendedRecipes]);
    }

}
