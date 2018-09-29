<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeRequest;
use App\Recipe;

class RecipeController extends Controller
{
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
        //
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
            if ($recipe) $recipe['ingredients'] = $ingredients;
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
    public function update(Request $request, $id)
    {
        $recipe = auth()->user()->recipes()->find($id);
        return response()->json($recipe);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $result = Recipe::deleteUserRecipeById($id);
            if ($result) {
                return response()->json(['message' => 'Recipe ' . $result->name . ' has been deleted']);
            }
            return response()->json(['error' => 'Recipe was not found by given id'], 422);
        } catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }
}
