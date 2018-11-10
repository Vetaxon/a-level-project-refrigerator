<?php

namespace App\Http\Controllers\API;

use App\Events\ClientEvent;
use App\Events\Messages\EventMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeRequest;
use App\Recipe;
use App\Repositories\RecipeRepository;
use App\Services\StoreIngredientsForRecipe;

class RecipeController extends Controller
{

    const PAGINATE_NUM = 9;

    protected $recipeRepo;

    /**
     * Display all recipes that belongs to user or default(null) user.
     *
     * @param RecipeRepository $recipeRepository
     */

    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepo = $recipeRepository;
    }

    public function index()
    {
        return response()->json($this->recipeRepo->getAllRecipesForUser(auth()->id())
            ->paginate(self::PAGINATE_NUM));
//        return response()->json(Recipe::getAllRecipesForUser(auth()->id())->paginate(self::PAGINATE_NUM));
    }

    /**
     * Store a newly created recipe for user in storage.
     *
     * @param RecipeRequest $request
     * @param StoreIngredientsForRecipe $store
     * @return Response \Illuminate\Http\JsonResponse
     * @internal param Recipe $newRecipe
     */
    public function store(RecipeRequest $request, StoreIngredientsForRecipe $store)
    {        
        $newRecipe = auth()->user()->recipes()->create($request->all());

        $store->storeMultipleIngredientForRecipe($newRecipe, $request->ingredients, auth()->id());
        
        $newRecipe->save();
        
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
        $recipe = $this->recipeRepo->getRecipeByIdForUser($id, auth()->id());
        
        return $recipe ? response()->json(['recipe' => $recipe]) : $this->messageRecipeNotFound();            
    }


    /**
     * Update the specified recipe for user in storage.
     *
     * @param RecipeRequest $request
     * @param Recipe $recipe
     * @param StoreIngredientsForRecipe $store
     * @return Response \Illuminate\Http\JsonResponse
     */
    public function update(RecipeRequest $request, Recipe $recipe, StoreIngredientsForRecipe $store)
    {
        if (!auth()->user()->owns($recipe)) {
            return $this->messageRecipeNorFound();
        }
             
        $recipe->fill($request->all());
        
        if (isset($request->ingredients)) {
            $recipe->ingredients()->detach();
            $store->storeMultipleIngredientForRecipe($recipe, $request->ingredients, auth()->id());
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
