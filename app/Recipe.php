<?php

namespace App;

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

    public function recipeIngredients()
    {
        return $this->hasMany('App\RecipeIngredient');
    }

    public function ingredients()
    {
        return $this->belongsToMany('App\Ingredient', 'recipe_ingredient')->withPivot('amount');

    }

    /**
     * @param $user_id
     * @return array of all recipes available for user
     */
    public static function getAllRecipesForUser($user_id)
    {
        return Recipe::with('ingredients')
            ->where('recipes.user_id', null)
            ->orWhere('recipes.user_id', $user_id)
            ->get();

    }

    /**Get a recipe by id if it is available for user
     * @param $user_id
     * @param $id
     * @return Recipe|Recipe[]|bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public static function getRecipeByIdForUser($user_id, $id)
    {
        $recipesRaw = Recipe::with('ingredients')->find($id);

        if (collect($recipesRaw)->isEmpty()) {
            return false;
        }

        if ($recipesRaw->user_id == null) {
            return $recipesRaw;
        }

        if ($recipesRaw->user_id == $user_id) {
            return $recipesRaw;
        }

        return false;

    }

    /**Prepare recipes for response
     * @param $recipesRaw
     * @return array
     */
    public static function transformRecipe($recipesRaw)
    {
        return collect($recipesRaw)->map(function ($item) {

            $recipe = [
                'id' => $item->id,
                'name' => $item->name,
                'text' => $item->text
            ];

            foreach ($item->ingredients as $ingredient) {

                $recipe['ingredients'][] = [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'amount' => $ingredient->pivot->amount,
                ];
            }

            return $recipe;

        })->all();
    }

}
