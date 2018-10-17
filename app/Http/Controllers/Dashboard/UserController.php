<?php

namespace App\Http\Controllers\Dashboard;

use App\Recipe;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('dashboard.users.index')
            ->withUsers(User::getAllUsersWithCounts()->get());
    }

    public function showIngredientsByUser(User $user)
    {
        return view('dashboard.users.ingredients')
            ->withIngredients($user->ingredients()->get())
            ->withUser($user);
    }

    public function showRefrigeratorsByUser(User $user)
    {

        return view('dashboard.users.refrigerator')
            ->withUser($user)
            ->withRefrigerator($user->refrigeratorIngredients()->get());

    }

    public function showRecipesByUser(User $user)
    {
        return view('dashboard.users.recipes')
            ->withUser($user)
            ->withRecipes(Recipe::getRecipeWithIngredientsByUser($user->id)->get());
    }

    public function storeUser(Request $request)
    {

    }
}
