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
    public static function getAllRecipesForUser($user_id = null)
    {
        return self::with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->select(['id', 'name', 'text'])
            ->where('recipes.user_id', null)
            ->orWhere('recipes.user_id', $user_id);
    }

    /**Get a recipe by id if it is available for user
     *
     * @param $id
     * @return Recipe|Recipe[]|bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public static function getRecipeByIdForUser($id, $user_id = null)
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

        if ($recipe->user_id == null || $recipe->user_id == $user_id) {
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
    public static function storeIngredientsForRecipe($recipe, $ingredients, $user = null)
    {
        foreach ($ingredients as $ingredient) {

            $ingredientName = key($ingredient);
            $validateIngredient['name'] = ucfirst(strtolower($ingredientName));

            if (self::validateIngredientName($validateIngredient)->fails()) {
                return false;
            }

            $user_id = (!$user == null) ? $user->id : null;

            $amount = array_values($ingredient)[0];
            $validatorExistsName = self::validateIngredientsExists($validateIngredient, $user_id);

            //Create a new ingredient if it does not exist for user and put it in recipe
            if ($validatorExistsName->fails()) {

                $validateIngredient['user_id'] = $user->id;
                $newIngredient = Ingredient::create($validateIngredient);

                $recipe->ingredients()->attach([$newIngredient->id => ['amount' => $amount]]);

            } else { // If an ingredient is available for user put it in a recipe

                $ingredientExistingId = Ingredient::getIngredientIdByName($validateIngredient, $user_id);

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
    protected static function validateIngredientsExists($ingredient, $user_id = null)
    {
        return Validator::make($ingredient, [
            'name' => [
                Rule::exists('ingredients')->where(function ($query) use ($user_id) {
                    $query->where('user_id', $user_id)->orWhere('user_id', null);
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
     * @param $request
     * @param $user_id
     * @return mixed
     */
    public static function createRecipe($request, $user_id)
    {
        return self::create([
            'name' => $request->name,
            'text' => $request->text,
            'user_id' => $user_id
        ]);
    }

    public static function getRecipeWithIngredientsByUser($user_id)
    {
        return self::with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->select(['id', 'name', 'text'])
            ->where('recipes.user_id', $user_id);
    }


}
