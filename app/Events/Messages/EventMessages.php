<?php

namespace  App\Events\Messages;

use App\Ingredient;
use App\Recipe;
use App\User;

class EventMessages
{    
    
    public static function userRegistered(User $user)
    {
        return 'User ' . $user->name . ' has been successfully registered!';
    }

    public static function userAddRecipe(Recipe $recipe)
    {
      return 'User ' . auth()->user()->name . ' has added a new recipe ' . $recipe->name . '!';
    }

    public static function userAddIngredient(Ingredient $ingredient)
    {
        return 'User ' . auth()->user()->name . ' has added a new ingredient ' . $ingredient->name . '!';
    }

    public static function userAddIngredInRefrig(Ingredient $ingredient)
    {
        return 'User ' . auth()->user()->name . ' has added a new ingredient ' . $ingredient->name . ' in refrigerator!';
    }

    public static function userGetRecommendedRecipes($count)
    {
        return 'User ' . auth()->user()->name . " got $count recommended recipes!";
    }

}