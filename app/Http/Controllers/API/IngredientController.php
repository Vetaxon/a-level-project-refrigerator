<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\IngredientRequest;
use App\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IngredientController extends Controller
{
    /**
     * Display all ingredients with measures.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if ($ingredients = Ingredient::getAllIngredientsForUser())
            return response()->json($ingredients);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param IngredientRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(IngredientRequest $request)
    {
        $newIngredient = $request->all();
        $newIngredient ['user_id'] = auth()->user()->id;

        if ($createNewIngredient = Ingredient::create($newIngredient)) {
            $message = 'Ingredient ' . $request->name . ' has been stored';
            return response()->json(['message' => $message]);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if ($ingredient = Ingredient::getIngredientByIdForUser($id))
            return response()->json($ingredient);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param IngredientRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(IngredientRequest $request, $id)
    {
        $ingredient = auth()->user()->ingredients()->find($id)->fill($request->all())->save();

        if ($ingredient) {
            $message = 'Ingredient ' . $request->name . ' has been updated';
            return response()->json(['message' => $message]);
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $ingredient = auth()->user()->ingredients()->find($id);
        $ingredientName = $ingredient->name;

        if ($ingredient->delete()) {
            $message = 'Ingredient ' . $ingredientName . ' has been deleted';
            return response()->json(['message' => $message]);
        }

        return response()->json(['message' => 'Deleting ingredients is forbidden, maybe it is used in recipes or refrigerator']);

    }
}
