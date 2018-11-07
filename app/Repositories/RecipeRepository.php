<?php

namespace App\Repositories;

use App\Contracts\RecipeRepositoryContract;
use App\ElasticSearch\Rules\RecipeSearchRuleFirst;
use App\ElasticSearch\Rules\RecipeSearchRuleForUser;
use App\ElasticSearch\Rules\RecipeSearchRuleSecond;
use App\ElasticSearch\Rules\RecipeSearchRuleThird;
use App\Ingredient;
use App\Recipe;
use App\ElasticSearch\Rules\RecipeSearchRule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RecipeRepository implements RecipeRepositoryContract
{
    public static function searchRecipeNullUser(string $search)
    {
        $recipe = Recipe::search($search)->with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->rule(RecipeSearchRuleFirst::class)
            ->whereNotExists('user_id')
            ->get();

        if(collect($recipe)->isNotEmpty()){
            return $recipe;
        }

        $recipe = Recipe::search($search)->with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->rule(RecipeSearchRuleSecond::class)
            ->whereNotExists('user_id')
            ->get();

        if(collect($recipe)->isNotEmpty()){
            return $recipe;
        }

        $recipe = Recipe::search($search)->with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->rule(RecipeSearchRuleThird::class)
            ->whereNotExists('user_id')
            ->get();

        return $recipe;
    }

    public static function searchRecipeForUser(string $search, int $user_id)
    {
        $recipe_user =  collect(Recipe::search($search)->with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->rule(RecipeSearchRuleForUser::class)
            ->where('user_id', $user_id)
            ->get());

        if($recipe_user->count() > 5){
            return $recipe_user;
        }

        $recipe_null =  Recipe::search($search)->with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->rule(RecipeSearchRuleForUser::class)
            ->whereNotExists('user_id')
            ->get();
        
        return $recipe_null;
        
    }


    public static function create(Collection $request, int $user_id = null)
    {
        $recipe = new Recipe();
        $recipe->fill($request->all());

        if($request->has('ingredients')){
            self::storeIngredientsForRecipe($recipe, $request->ingredients, $user_id);
        }
        
        $recipe->save();

    }

    public static function update(Collection $request, Recipe $recipe, int $user_id = null)
    {
        $recipe->fill($request->all())->save();
    }

    public static function destroy(Recipe $recipe, int $user_id = null)
    {
        // TODO: Implement destroy() method.
    }


    /**
     *
     * Store ingredients for recipes in recipe_ingredient table after storing new ingredients for user if does not exists
     * @param $recipe
     * @param $ingredients
     * @return bool
     */
    public static function storeIngredientsForRecipe(Recipe $recipe, $ingredients, $user_id = null)
    {
        foreach ($ingredients as $ingredient) {
            $validIngredient['name'] = mb_convert_case(key($ingredient), MB_CASE_TITLE, "UTF-8");;
            $validIngredient['amount'] = array_values($ingredient)[0];
            self::storeOneIngredientForRecipe($recipe, $validIngredient, $user_id);
        }
        return true;
    }

    /**
     * @param $recipe
     * @param $ingredient
     * @param null $user_id
     * @return void
     */
    public static function storeOneIngredientForRecipe($recipe, $ingredient, $user_id = null)
    {
        $ingredient['name'] = mb_convert_case($ingredient['name'], MB_CASE_TITLE, "UTF-8");
        $validatorExistsName = self::validateIngredientsExists($ingredient, $user_id);

        //Create a new ingredient if it does not exist for user and put it in recipe
        if ($validatorExistsName->fails()) {

            $ingredient['user_id'] = $user_id;
            $newIngredient = Ingredient::create($ingredient);
            $recipe->ingredients()->attach([$newIngredient->id => ['amount' => $ingredient['amount']]]);

        } else { // If an ingredient is available for user put it in a recipe

            $ingredientExistingId = Ingredient::getIngredientIdByName($ingredient, $user_id);
            $recipe->ingredients()->attach([$ingredientExistingId => ['amount' => $ingredient['amount']]]);
        }
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
}