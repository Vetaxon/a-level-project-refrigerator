<?php

namespace App\Http\Controllers\API;

use App\Events\ClientEvent;
use App\Events\Messages\EventMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeRequest;
use App\Recipe;
use Illuminate\Http\Response;

class RecipeController extends Controller
{

    const PAGINATE_NUM = 9;

    /**
     * Display all recipes that belongs to user or default(null) user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Recipe::getAllRecipesForUser(auth()->id())->paginate(self::PAGINATE_NUM));
    }

    /**
     * Store a newly created recipe for user in storage.
     *
     * @param RecipeRequest $request
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function store(RecipeRequest $request)
    {
        $newRecipe = Recipe::createRecipe($request, auth()->id());

        if (Recipe::storeIngredientsForRecipe($newRecipe, $request->ingredients, auth()->id())) {

            $message = EventMessages::userAddRecipe($newRecipe);
            activity()->withProperties($message)->log('messages');
            ClientEvent::dispatch($message);

            return $this->show($newRecipe->id);
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
        if ($recipe = Recipe::getRecipeByIdForUser($id, auth()->id())) {
            return response()->json(['recipe' => $recipe]);
        }

        return response()->json(['error' => 'Recipe was not found for current user'], 422);
    }


    /**
     * Update the specified recipe for user in storage.
     *
     * @param RecipeRequest $request
     * @param Recipe $recipe
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function update(RecipeRequest $request, Recipe $recipe)
    {
        if (!auth()->user()->owns($recipe)) {
            return response()->json(['error' => 'Recipe was not found for current user'], 422);
        }

        if ($request->has('name') and $recipe->name != $request->name) {
            $recipe->fill(['name' => $request->name]);
        }

        if ($request->has('text')) {
            $recipe->fill(['text' => $request->text]);
        }

        $recipe->save();

        if (!$request->ingredients) {
            return $this->show($recipe->id);
        }

        $recipe->ingredients()->detach();

        if (Recipe::storeIngredientsForRecipe($recipe, $request->ingredients, auth()->id())) {
            return $this->show($recipe->id);
        }

    }

    /**
     * Remove the specified user's recipe from storage.
     *
     * @param Recipe $recipe
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function destroy(Recipe $recipe)
    {
        if (!auth()->user()->owns($recipe)) {
            return response()->json(['error' => 'Recipe was not found for current user'], 422);
        }

        if ($recipe->delete()) {
            return response()->json(['message' => 'Recipe ' . $recipe->name . ' has been deleted']);
        }
    }


}
