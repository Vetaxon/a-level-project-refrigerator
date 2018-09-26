<?php

namespace App\Http\Controllers\API;

use App\Ingredient;
use App\Http\Controllers\Controller;
use App\Http\Requests\IngredientRequest;

class IngredientController extends Controller
{
    /**
     * Display all ingredients that belongs to user with default(null) ingredients.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {

            $userIngredients = auth()->user()->ingredients()->orderBy('name')->get(['id', 'name', 'measure']);

            $generalIngredients = Ingredient::where('user_id', null)->orderBy('name')->get(['id', 'name', 'measure']);

            return response()->json(['ingredients' => $userIngredients->merge($generalIngredients)]);

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
        if ($validate = $this->validateNameForNullUser($request)) {
            return response()->json($validate, 422);
        }

        $newIngredient = $request->all();
        $newIngredient ['user_id'] = auth()->user()->id;

        try {
            return response()->json([
                'message' => 'Ingredient ' . $request->name . ' has been stored',
                'ingredient' => Ingredient::create($newIngredient)->only(['id', 'name', 'measure'])
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

            if (count($ingredient = auth()->user()->ingredients()->find($id)->only(['id', 'name', 'measure'])) > 0) {
                return response()->json(['ingredient' => $ingredient]);
            }

            return response()->json(['error' => 'Ingredient was not found for current user'], 422);

        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 422);
        }
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
        if ($validate = $this->validateNameForNullUser($request)) {
            return response()->json($validate, 422);
        }

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

    /**
     * Validate $request->name for exists for default user(null)
     * @param $request
     * @return array|bool
     */
    protected function validateNameForNullUser($request)
    {

        if (Ingredient::where('user_id', null)->where('name', $request->name)->first()) {
            return [
                'message' => 'The given data was invalid.',
                'errors' => ['name' => ['The name has already been taken']]];
        }

        return false;

    }
}
