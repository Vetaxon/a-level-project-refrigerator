<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeRequest;
use App\Recipe;

class RecipeController extends Controller
{
    protected $getParameters = ['user_id', 'name', 'text'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return response()->json(Recipe::getUserAllRecipes());
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecipeRequest $request)
    {
        try {
            $resultAll = $request->all();
            $resultAll ['user_id'] = auth()->user()->id;

            return response()->json([
                'message' => 'Recipe ' . $request->name . ' has been stored',
                'recipe' => Recipe::create($resultAll)->only($this->getParameters)
            ]);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $recipe = (array)Recipe::getUserRecipeById($id);
            $ingredients = Recipe::getUserRecipeIngredientsById($id);
            if ($recipe) {
                $recipe['ingredients'] = $ingredients;
            }
            return response()->json($recipe);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RecipeRequest $request, $id)
    {
        try {
            $recipe = auth()->user()->recipes()->find($id);
            if ($recipe && $recipe->fill($request->only($this->getParameters))->save()) {
                return response()->json([
                    'message' => 'Recipe ' . $request->name . ' has been updated',
                    'recipe' => $request->only($this->getParameters)
                ]);
            }
            return response()->json(['error' => 'No available recipe by given id'], 422);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $recipe = auth()->user()->recipes()->find($id);
            if ($recipe) {
                $recipe->delete();
                return response()->json(['message' => 'Recipe ' . $recipe->name . ' has been deleted']);
            }
            return response()->json(['error' => 'No available recipe by given id'], 422);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }
}
