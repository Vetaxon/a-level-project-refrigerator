<?php

namespace App\Http\Controllers\API;

use App\Recipe;
use App\Refrigerator;
use App\Http\Controllers\Controller;

class RefrigeratorRecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userRefrigerator = Refrigerator::where('user_id', 1)->get();

        $recipes = Recipe::with('ingredients')->get();


        echo "<pre>";
        dump($userRefrigerator);
        echo "<pre>";
        dump($recipes);

        foreach ($recipes as $recipe) {

            $recipeIngredientCount = count($recipe['ingredients']) . '<br>';

            $ingredientMatches = 0;

            foreach ($recipe['ingredients'] as $recipeIngredient) {

                foreach ($userRefrigerator as $refrigeratorIngredient) {

                    if ($recipeIngredient['id'] == $refrigeratorIngredient['ingredient_id']
                        and $recipeIngredient['pivot']['value'] <= $refrigeratorIngredient['value']) {

                        $ingredientMatches++;
                    }
                }
            }

            if ($recipeIngredientCount == $ingredientMatches) {

                $recommendedRecipes[] = $recipe;
            }
        }

        echo "<pre>";
        dump($recommendedRecipes);

        return response()->json($recommendedRecipes);

    }


}
