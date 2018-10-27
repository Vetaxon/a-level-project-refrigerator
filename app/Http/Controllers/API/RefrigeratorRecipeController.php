<?php

namespace App\Http\Controllers\API;

use App\Events\ClientEvent;
use App\Events\Messages\EventMessages;
use App\Recipe;
use App\Http\Controllers\Controller;

class RefrigeratorRecipeController extends Controller
{

    const PAGINATE_RECIPES = 9;


    /**
     * Get all recommended recipes for user according to refrigerator's ingredients.
     *
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $refrigerator = auth()->user()->refrigeratorIngredients()->get()->toArray();
        $recipes = Recipe::getAllRecipesForUser(auth()->id())->get()->toArray();
        
        $recommendedRecipesIds = $this->getRecommendedRecipesdIds($recipes, $refrigerator);

        $recommendedRecipes = Recipe::getRecipesByMultipleIds($recommendedRecipesIds)
            ->paginate(self::PAGINATE_RECIPES);

        ClientEvent::dispatch(EventMessages::userGetRecommendedRecipes(count($recommendedRecipesIds)));

        return response()->json($recommendedRecipes);
    }


    /**
     * Get get id's array of all recommended recipes for user due to ingredients in refrigerator
     * @param $recipes - all recipes available for user
     * @param $refrigerator - user's ingredients in a refrigerator
     * @return array
     */
    protected function getRecommendedRecipesdIds($recipes, $refrigerator)
    {
        $recommendedRecipesIds = [];

        foreach ($recipes as $recipe) {

            $recipeIngredientCount = count($recipe['ingredients']);
            $ingredientMatches = 0;

            foreach ($recipe['ingredients'] as $recipeIngredient) {
                foreach ($refrigerator as $refrigeratorIngredient) {
                    if ($recipeIngredient['id'] == $refrigeratorIngredient['id']) {
                        $ingredientMatches++;
                    }
                }
            }

            if ($recipeIngredientCount == $ingredientMatches) {
                $recommendedRecipesIds[] = $recipe['id'];
            }
        }

        return $recommendedRecipesIds;
    }

}
