<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\RecipeRequest;
use App\Ingredient;
use App\RecipeIngredient;
use App\Http\Controllers\Controller;
use App\Recipe;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class RecipeController extends Controller
{
    /**
     * Display all recipes that belongs to user or default(null) user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $recipes = Recipe::getAllRecipesForUser(auth()->id());

            return response()->json(['recipes' => Recipe::transformRecipe($recipes)]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Store a newly created recipe for user in storage.
     *
     * @param RecipeRequest $request
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function store(RecipeRequest $request)
    {
        try {

            $newRecipe = Recipe::create([
                'name' => $request->name,
                'text' => $request->text,
                'user_id' => auth()->id()
            ]);

            if ($this->storeIngredientsForRecipe($newRecipe, $request->ingredients)) {
                return $this->show($newRecipe->id);
            }


        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    /**
     * Display the specified recipe if it is available for user.
     *
     * @param  int $id
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            if ($recipe = Recipe::getRecipeByIdForUser(auth()->id(), $id)) {
                return response()->json(['recipe' => Recipe::transformRecipe([$recipe])]);
            }

            return response()->json(['error' => 'Recipe was not found for current user'], 422);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }

    }


    /**
     * Update the specified recipe for user in storage.
     *
     * @param RecipeRequest $request
     * @param  int $id
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function update(RecipeRequest $request, $id)
    {
        try {

            if (!$recipe = Recipe::getRecipeByIdForUser(auth()->id(), $id)) {
                return response()->json(['error' => 'Recipe was not found for current user'], 422);
            }

            if ($request->has('name') and $recipe->name != $request->name) {
                $updateRecipe = $recipe->fill(['name' => $request->name]);
            }

            if ($request->has('text')) {
                $updateRecipe = $recipe->fill(['text' => $request->text]);
            }

            $recipe->save();

            if (!$request->ingredients) {
                return $this->show($recipe->id);
            }

            RecipeIngredient::where('recipe_id', $recipe->id)->delete();

            if ($this->storeIngredientsForRecipe($recipe, $request->ingredients)) {
                return $this->show($recipe->id);
            }


        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    /**
     * Remove the specified user's recipe from storage.
     *
     * @param  int $id
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try{
            if (!$recipe = Recipe::getRecipeByIdForUser(auth()->id(), $id)) {
                return response()->json(['error' => 'Recipe was not found for current user'], 422);
            }

            if ($recipe->delete()){
                return response()->json(['message' => 'Recipe ' . $recipe->name . ' has been deleted']);
            }

        }catch (\Exception $exception){

            return response()->json(['error' => $exception->getMessage()], 422);
        }

    }

    /**
     * Validate ingredient if exist in ingredients and available for user
     * @param $ingredient
     * @return mixed
     */
    protected function validateIngredientsExists($ingredient)
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
     *
     * Store ingredients for recipes in recipe_ingredient table after storing new ingredients for user if does not exists
     * @param $recipe
     * @param $ingredients
     * @return bool
     */
    protected function storeIngredientsForRecipe($recipe, $ingredients)
    {
        foreach ($ingredients as $ingredient) {

            $validateIngredient['name'] = key($ingredient);
            $amount = array_values($ingredient)[0];
            $validator = $this->validateIngredientsExists($validateIngredient);

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

}
