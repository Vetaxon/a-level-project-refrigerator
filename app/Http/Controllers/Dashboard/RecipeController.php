<?php

namespace App\Http\Controllers\Dashboard;

use App\Recipe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::getAllRecipesForUser(null)->get();


        return view('dashboard.recipes')->withRecipes($recipes);
    }

    public function store()
    {


    }

    public function show($id)
    {
        $recipe = Recipe::getRecipeByIdForUser($id, null);

        dd($recipe);
    }
}
