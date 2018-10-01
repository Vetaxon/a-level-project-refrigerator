<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        return $this->belongsToMany('App\Ingredient', 'recipe_ingredient')
            ->withPivot('amount');

    }

    /**
     * @return Recipe|\Illuminate\Database\Eloquent\Builder
     */
    public static function getAllRecipesForUser()
    {
        return self::with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->select(['id', 'name', 'text'])
            ->where('recipes.user_id', null)
            ->orWhere('recipes.user_id', auth()->id());
    }

    /**Get a recipe by id if it is available for user
     *
     * @param $id
     * @return Recipe|Recipe[]|bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public static function getRecipeByIdForUser($id)
    {
        $recipe =  self::with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->select(['id', 'name', 'text'])
            ->find($id);

        if (collect($recipe)->isEmpty()) {
            return false;
        }

        if ($recipe->user_id == null) {
            return $recipe;
        }

        if ($recipe->user_id == auth()->id()) {
            return $recipe;
        }

        return false;

    }


    /**
     *
     * Store ingredients for recipes in recipe_ingredient table after storing new ingredients for user if does not exists
     * @param $recipe
     * @param $ingredients
     * @return bool
     */
    public static function storeIngredientsForRecipe($recipe, $ingredients)
    {
        foreach ($ingredients as $ingredient) {

            $validateIngredient['name'] = key($ingredient);
            $amount = array_values($ingredient)[0];
            $validator = self::validateIngredientsExists($validateIngredient);

            //Create a new ingredient if it does not exist for user and put it in recipe
            if ($validator->fails()) {

                $validateIngredient['user_id'] = auth()->id();
                $newIngredient = Ingredient::create($validateIngredient);
                $recipe->ingredients()->attach([$newIngredient->id => ['amount' => $amount]]);

            } else { // If an ingredient is available for user put it in a recipe

                $ingredientExistingId = Ingredient::where('name', $validateIngredient['name'])
                    ->where('user_id', auth()->id())->orWhere('user_id', null)->first()->id;

                $recipe->ingredients()->attach([$ingredientExistingId => ['amount' => $amount]]);
            }
        }

        return true;

    }

    /**
     * Validate ingredient if exist in ingredients and available for user
     * @param $ingredient
     * @return mixed
     */
    protected static function validateIngredientsExists($ingredient)
    {
        return Validator::make($ingredient, [
            'name' => [
                Rule::exists('ingredients')->where(function ($query) {
                    $query->where('user_id', auth()->id())->orWhere('user_id', null);
                }),
            ]
        ]);
    }


}
