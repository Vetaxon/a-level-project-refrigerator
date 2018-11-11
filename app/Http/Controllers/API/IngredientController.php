<?php

namespace App\Http\Controllers\API;

use App\Events\ClientEvent;
use App\Events\Messages\EventMessages;
use App\Ingredient;
use App\Http\Controllers\Controller;
use App\Http\Requests\IngredientRequest;
use App\Repositories\IngredientRepository;
use App\Services\StoreIngredientsForRecipe;

class IngredientController extends Controller
{
    protected $ingredientRepo;

    protected $getParameters = ['id', 'name', 'user_id'];

    /**
     * IngredientController constructor.
     * @param IngredientRepository $ingredientRepository
     */
    public function __construct(IngredientRepository $ingredientRepository)
    {
        $this->ingredientRepo = $ingredientRepository;
    }


    /**
     * Display all ingredients that belongs to user with default(null) ingredients.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'ingredients' => $this->ingredientRepo->getAllUsersIngredient(auth()->id())->get(),
        ]);
    }


    /**
     * Store a newly created ingredient by auth user.
     *
     * @param IngredientRequest $request
     * @param StoreIngredientsForRecipe $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(IngredientRequest $request, StoreIngredientsForRecipe $store)
    {
        $ingredient = $store->storeIngredient($request, auth()->id());

        $message = EventMessages::userAddIngredient($ingredient);

        activity()->withProperties($message)->log('messages');

        ClientEvent::dispatch($message);

        return response()->json([
            'message' => 'Ingredient ' . $ingredient->name . ' has been stored',
            'ingredient' => $ingredient->only($this->getParameters)
        ]);
    }


    /**
     * Display the specified ingredient that can belong to user or default user(null).
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (!$ingredient = $this->ingredientRepo->getUsersIngredientById($id, auth()->id())) {
            return $this->messageIngredientNotFound();
        }

        return response()->json(['ingredient' => $ingredient->only($this->getParameters)]);

    }


    /**
     * Update the specified ingredient that belongs to user in storage.
     *
     * @param IngredientRequest $request
     * @param  int $id
     * @param StoreIngredientsForRecipe $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(IngredientRequest $request, $id, StoreIngredientsForRecipe $store)
    {
        if (!$ingredient = auth()->user()->ingredients()->find($id)) {
            return $this->messageIngredientNotFound();
        }

        $ingredient->update(['name' => $store->ingredientNameConvertCase($request->name)]);

        return $this->show($id);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (!$ingredient = auth()->user()->ingredients()->find($id)) {
            return $this->messageIngredientNotFound();
        }

        $ingredient->delete();

        return response()->json(['message' => 'Ingredient ' . $ingredient->name . ' has been deleted']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function messageIngredientNotFound()
    {
        return response()->json(['error' => 'Ingredient was not found for current user'], 422);
    }

}
