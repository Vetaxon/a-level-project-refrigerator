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
     * @param Recipe $newRecipe
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function store(RecipeRequest $request, Recipe $newRecipe)
    {
        Recipe::storeIngredientsForRecipe($newRecipe, $request->ingredients, auth()->id());
        
        $newRecipe->fill($request->all())->save();
        
        $message = EventMessages::userAddRecipe($newRecipe);
        
        activity()->withProperties($message)->log('messages');
        
        ClientEvent::dispatch($message);

        return $this->show($newRecipe->id);

    }

    /**
     * Display the specified recipe if it is available for user.
     *
     * @param  int $id
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $recipe = Recipe::getRecipeByIdForUser($id, auth()->id());
        
        return $recipe ? response()->json(['recipe' => $recipe]) : $this->messageRecipeNotFound();            
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
            return $this->messageRecipeNorFound();
        }
             
        $recipe->fill($request->all());
        
        if ($request->ingredients) {
            $recipe->ingredients()->detach();
            Recipe::storeIngredientsForRecipe($recipe, $request->ingredients, auth()->id());
        }
        
        $recipe->save();
        
        return $this->show($recipe->id);      

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
            return $this->messageRecipeNorFound();
        }

        if ($recipe->delete()) {
            return response()->json(['message' => 'Recipe ' . $recipe->name . ' has been deleted']);
        }
    }
    
    protected function messageRecipeNotFound()
    {
        return response()->json(['error' => 'Recipe was not found for current user'], 422);
    }


}
