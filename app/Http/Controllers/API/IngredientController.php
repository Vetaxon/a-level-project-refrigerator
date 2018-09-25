<?php

namespace App\Http\Controllers\API;

use App\Ingredient;
use App\Http\Controllers\Controller;
use App\Http\Requests\IngredientRequest;

class IngredientController extends Controller
{
    /**
     * Display all ingredients with measures.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            return response()->json(Ingredient::getAllIngredientsForUser());
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
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

        try {
            return response()->json([
                'message' => 'Ingredient ' . $request->name . ' has been stored',
                'ingredient' => Ingredient::create($newIngredient)
            ]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
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
        try {

            if (count($ingredient = Ingredient::getIngredientByIdForUser($id)) > 0) {
                return response()->json($ingredient);
            }

            return response()->json(['error' => 'Ingredient was not found for current user'], 422);

        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 422);
        }
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
        $ingredient = $ingredient = auth()->user()->ingredients()->find($id);

        if (!$ingredient) {
            return response()->json(['error' => 'Ingredient was not found for current user'], 422);
        }

        try {
            $ingredient->fill($request->all())->save();
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }

        if ($ingredient) {
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
            return response()->json([
                'error' => 'Deleting ingredients is forbidden, maybe it is used in recipes or in refrigerator'
            ], 422);
        }

        return response()->json(['message' => 'Ingredient has been deleted']);
    }
}
