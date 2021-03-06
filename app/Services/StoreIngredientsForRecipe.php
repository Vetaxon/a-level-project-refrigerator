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
use App\Repositories\IngredientRepository;
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
    public function storeMultipleIngredientForRecipe(Recipe $recipe, $ingredients, $user_id = null)
    {
        foreach ($ingredients as $ingredient) {
            $validIngredient['name'] = key($ingredient);
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
    public function storeOneIngredientForRecipe(Recipe $recipe, $ingredient, $user_id = null)
    {
        $ingredient['name'] = $this->ingredientNameConvertCase($ingredient['name']);

        $validatorExistsName = $this->validateIngredientsExists($ingredient, $user_id);

        if ($validatorExistsName->fails()) {

            $this->addToRecipeNotExistedIngredient($recipe, $ingredient, $user_id);

        } else {

            $this->addToRecipeExistedIngredient($recipe, $ingredient, $user_id);
        }
    }

    /**
     * @param string $ingredient
     * @return bool|mixed|null|string|string[]
     */
    public function ingredientNameConvertCase(string $ingredient)
    {
        return mb_convert_case($ingredient, MB_CASE_TITLE, "UTF-8");
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

    /**
     * @param Recipe $recipe
     * @param array $ingredient
     * @param $user_id
     */
    protected function addToRecipeExistedIngredient(Recipe $recipe, array $ingredient, $user_id)
    {
        $ingredientExistingId = $this->getIngredientIdByName($ingredient, $user_id);

        $this->validateIngredient($recipe->id, $ingredientExistingId);

        $recipe->ingredients()->attach([$ingredientExistingId => ['amount' => $ingredient['amount']]]);
    }

    /**
     * @param $request
     * @param null $user_id
     * @return mixed
     */
    public function storeIngredient($request, $user_id = null)
    {
        return Ingredient::create([
            'name' => $this->ingredientNameConvertCase($request->name),
            'user_id' => $user_id,
        ]);
    }

    /**Get Ingredient id by name for user
     * @param $ingredient
     * @param null $user_id
     * @return mixed
     */
    public function getIngredientIdByName($ingredient, $user_id = null)
    {
        return Ingredient::where('name', $ingredient['name'])
            ->where(function ($query) use ($user_id) {
                return $query->whereNull('user_id')
                    ->orWhere('user_id', $user_id);
            })->first()->id;
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