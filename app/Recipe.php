<?php

namespace App;

use Illuminate\Support\Facades\DB;
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
        $recipe = self::with([
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

            $ingredientName = key($ingredient);
            $validateIngredient['name'] = ucfirst(strtolower($ingredientName));

            if (self::validateIngredientName($validateIngredient)->fails()) {
                return false;
            }

            $amount = array_values($ingredient)[0];
            $validatorExistsName = self::validateIngredientsExists($validateIngredient);

            //Create a new ingredient if it does not exist for user and put it in recipe
            if ($validatorExistsName->fails()) {

                $validateIngredient['user_id'] = auth()->id();
                $newIngredient = auth()->user()->ingredients()->create($validateIngredient);

                $recipe->ingredients()->attach([$newIngredient->id => ['amount' => $amount]]);

            } else { // If an ingredient is available for user put it in a recipe

                $ingredientExistingId = Ingredient::getIngredientIdByName($validateIngredient);

                $recipe->ingredients()->attach([$ingredientExistingId => ['amount' => $amount]]);
            }
        }

        return true;

    }

    /**Get recipes by multiple ids
     * @param $ids
     * @return Recipe|\Illuminate\Database\Eloquent\Builder
     */
    public static function getRecipesByMultipleIds($ids)
    {
        return self::with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->select(['id', 'name', 'text'])
            ->whereIn('recipes.id', $ids);
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


    /**
     * @param $ingredient
     * @return mixed
     */
    protected static function validateIngredientName($ingredient)
    {
        return Validator::make($ingredient, [
            'name' => 'required|string|max:255'
        ]);
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
