<?php

namespace  App\Events\Messages;

use App\Ingredient;
use App\Recipe;
use App\User;
use Carbon\Carbon;

class EventMessages
{    
    
    public static function userRegistered(User $user)
    {
        return 'User ' . $user->name . ' has been successfully registered!' . ' at ' . Carbon::now();
    }

    public static function userAddRecipe(Recipe $recipe)
    {
      return 'User ' . auth()->user()->name . ' has added a new recipe ' . $recipe->name . '!' . ' at ' . Carbon::now();
    }

    public static function userAddIngredient(Ingredient $ingredient)
    {
        return 'User ' . auth()->user()->name . ' has added a new ingredient ' . $ingredient->name . '!' . ' at ' . Carbon::now();
    }

    public static function userAddIngredInRefrig(Ingredient $ingredient)
    {
        return 'User ' . auth()->user()->name . ' has added a new ingredient ' . $ingredient->name . ' in refrigerator!' . ' at ' . Carbon::now();
    }

    public static function userGetRecommendedRecipes($count)
    {
        return 'User ' . auth()->user()->name . " got $count recommended recipes!" . ' at ' . Carbon::now();
    }

}