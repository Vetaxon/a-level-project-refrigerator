<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'name', 'text', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return mixed
     */
    public static function getUserAllRecipes()
    {
        return DB::table('recipes')
            ->select('recipes.id',
                'recipes.name as recipe',
                'recipes.text'
            )
            ->where('recipes.user_id', auth()->user()->id)
            ->OrWhere('recipes.user_id', null)
            ->orderBy('recipes.id')
            ->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getUserRecipeIngredientsById($id)
    {
        return DB::table('recipe_ingredient')
            ->select('recipe_ingredient.ingredient_id',
                'ingredients.name',
                'recipe_ingredient.amount')
            ->join('ingredients', 'recipe_ingredient.ingredient_id', '=', 'ingredients.id')
            ->where('recipe_ingredient.recipe_id', $id)
            ->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getUserRecipeById($id)
    {
        return DB::table('recipes')
            ->select('recipes.id',
                'recipes.name as recipe',
                'recipes.text')
            ->where([['recipes.user_id', auth()->user()->id], ['recipes.id', $id]])
            ->oRwhere([['recipes.user_id', null], ['recipes.id', $id]])
            ->first();
    }
}