<?php

namespace App\Http\Controllers\API;

use App\Ingredient;
use App\Http\Controllers\Controller;
use App\Http\Requests\IngredientRequest;

class IngredientController extends Controller
{

    protected $getParameters = ['id', 'name'];


    /**
     * Display all ingredients that belongs to user with default(null) ingredients.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public function index()
    {
        try {

            return response()->json(['ingredients' => Ingredient::getAllUsersIngredient($this->getParameters)]);

        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }


    /**
     * Store a newly created ingredient by auth user.
     *
     * @param IngredientRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(IngredientRequest $request)
    {
        $newIngredient['name'] = ucfirst(strtolower($request->name));
        $newIngredient ['user_id'] = auth()->user()->id;

        try {
            return response()->json([
                'message' => 'Ingredient ' . $request->name . ' has been stored',
                'ingredient' => Ingredient::create($newIngredient)->only($this->getParameters)
            ]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }

    }


    /**
     * Display the specified ingredient that can belong to user or default user(null).
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $ingredient = Ingredient::getUsersIngredientById($id);
        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 422);
        }

        if ($ingredient) {
            return response()->json(['ingredient' => $ingredient->only($this->getParameters)]);
        }

        return response()->json(['error' => 'Ingredient was not found for current user'], 422);
    }


    /**
     * Update the specified ingredient that belongs to user in storage.
     *
     * @param IngredientRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(IngredientRequest $request, $id)
    {
        $ingredient = $ingredient = auth()->user()->ingredients()->find($id);

        if (!$ingredient) {
            return response()->json(['error' => 'Ingredient was not found for current user'], 422);
        }

        try {

            $updatedIngredient['name'] = ucfirst(strtolower($request->name));
            $ingredient->fill($updatedIngredient)->save();

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }

        if ($updatedIngredient) {
            return $this->show($id);
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

        if (!$ingredient) {
            return response()->json(['error' => 'Ingredient was not found for current user'], 422);
        }

        try {
            $ingredient->delete();

        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 422);
        }

        return response()->json(['message' => 'Ingredient ' . $ingredient->name . ' has been deleted']);
    }

}
