<?php

namespace App\Http\Controllers\API;

use App\Recipe;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

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
        try {

            $refrigerator = auth()->user()->refrigerators()->get();
            $recipes = Recipe::getAllRecipesForUser()->get();

            $recommendedRecipes = Recipe::getRecipesByMultipleIds($this->getRecommendedRecipesdIds($recipes, $refrigerator))
                ->paginate(self::PAGINATE_RECIPES);

            return response()->json($recommendedRecipes);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }

    }


    /**
     * Get get id's array of all recommended recipes for user due to ingredients in refrigerator
     * @param $recipes - all recipes available for user
     * @param $refrigerator - user's ingredients in a refrigerator
     * @return array
     */
    protected function getRecommendedRecipesdIds($recipes, $refrigerator): array
    {
        foreach ($recipes as $recipe) {

            $recipeIngredientCount = count($recipe['ingredients']);
            $ingredientMatches = 0;

            foreach ($recipe['ingredients'] as $recipeIngredient) {
                foreach ($refrigerator as $refrigeratorIngredient) {
                    if ($recipeIngredient['id'] == $refrigeratorIngredient['ingredient_id']) {
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
