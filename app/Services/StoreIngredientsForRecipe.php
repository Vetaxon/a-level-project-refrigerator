<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 10.11.18
 * Time: 14:45
 */

namespace App\Services;

use App\Ingredient;
use App\Recipe;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreIngredientsForRecipe
{
    /**
     *Store ingredients for recipe
     * Store ingredients for recipes in recipe_ingredient table after storing new ingredients for user if does not exists
     * @param $recipe
     * @param $ingredients
     * @param null $user_id
     * @return bool
     */
    public function storeMultipleIngredientForRecipe($recipe, $ingredients, $user_id = null)
    {
        foreach ($ingredients as $ingredient) {
            $validIngredient['name'] = mb_convert_case(key($ingredient), MB_CASE_TITLE, "UTF-8");;
            $validIngredient['amount'] = array_values($ingredient)[0];
            $this->storeOneIngredientForRecipe($recipe, $validIngredient, $user_id);
        }
        return true;
    }

    /**
     * Store one ingredient for recipe
     * @param $recipe
     * @param $ingredient
     * @param null $user_id
     * @return void
     */
    public function storeOneIngredientForRecipe($recipe, $ingredient, $user_id = null)
    {
        $ingredient['name'] = mb_convert_case($ingredient['name'], MB_CASE_TITLE, "UTF-8");

        $validatorExistsName = $this->validateIngredientsExists($ingredient, $user_id);

        if ($validatorExistsName->fails()) {

            $this->addToRecipeNotExistedIngredient($recipe, $ingredient, $user_id);

        } else {

            $this->addToRecipeExistedIngredient($recipe, $ingredient, $user_id);
        }
    }

    /**
     * @param Recipe $recipe
     * @param array $ingredient
     * @param int $user_id
     */
    protected function addToRecipeNotExistedIngredient(Recipe $recipe, array $ingredient, $user_id)
    {
        $ingredient['user_id'] = $user_id;

        Validator::make($ingredient, ['name' => 'string|max:255'])->validate();

        $newIngredient = Ingredient::create($ingredient);

        $recipe->ingredients()->attach([$newIngredient->id => ['amount' => $ingredient['amount']]]);
    }

    protected function addToRecipeExistedIngredient(Recipe $recipe, array $ingredient, $user_id)
    {
        $ingredientExistingId = Ingredient::getIngredientIdByName($ingredient, $user_id);

        $this->validateIngredient($recipe->id, $ingredientExistingId);

        $recipe->ingredients()->attach([$ingredientExistingId => ['amount' => $ingredient['amount']]]);
    }

    /**
     * Validate ingredient if exist in ingredients and available for user
     * @param $ingredient
     * @return mixed
     */
    protected function validateIngredientsExists($ingredient, $user_id = null)
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
     * Validate ingredient if exist for specified recipe
     * @param $recipe_id
     * @param $ingredient_id
     * @return mixed
     * @internal param $ingredient
     */
    protected function validateIngredient($recipe_id, $ingredient_id)
    {        
        $parameters['ingredient_id'] = $ingredient_id;

        $messages = [
            'ingredient_id.unique' => 'The ingredient has already been added to the recipe.',
        ];
        
        Validator::make($parameters, [
            'ingredient_id' => [
                Rule::unique('recipe_ingredient')->where(function ($query) use ($recipe_id) {
                    $query->where('recipe_id', $recipe_id);
                }),
            ],
        ], $messages)->validate();
    }
}