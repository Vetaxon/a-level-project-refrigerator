<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreRefrigeratorRequest;
use App\Http\Requests\UpdateRefrigeratorRequest;
use App\Ingredient;
use App\Refrigerator;
use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class RefrigeratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(iterator_to_array($this->getFormatIngredients()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRefrigeratorRequest $request)
    {
        $request->merge(['user_id' => Auth::user()->id]);
        Validator::make($request->all(), ['ingredient_id' => 'unique_with:refrigerators,user_id',])->validate();
        $user = Auth::user();
        $ingredient = Ingredient::find($request->ingredient_id);

        if ($user->owns($ingredient) || $ingredient->user_id === null) {
            $user->refrigeratorIngredients()->attach($ingredient, ['amount' => $request->amount]);
            return response()->json($this->formatIngredient($user->refrigeratorIngredients->find($request->ingredient_id)));
        }
        return response()->json(['error' => 'Current user can not do this!'], 403);
    }

    /**
     * Display the specified resource.
     *
     * @param Ingredient $ingredient
     * @return \Illuminate\Http\Response
     */
    public function show(Ingredient $ingredient)
    {
        if ($ingredient = Auth::user()->refrigeratorIngredients->find($ingredient)) {
            return response()->json($this->formatIngredient(Auth::user()->refrigeratorIngredients->find($ingredient)));
        }
        return response()->json(['error' => 'There is no such ingredient in refrigerator!'], 404);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRefrigeratorRequest $request
     * @param Ingredient $ingredient
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRefrigeratorRequest $request, Ingredient $ingredient)
    {
        if ((!$ingredient = Auth::user()->refrigeratorIngredients->find($ingredient))) {
            return response()->json(['error' => 'Current user can not do this!'], 403);
        }
            $ingredient->pivot->amount = $request->amount;
            if ($ingredient->pivot->save()) {
                return response()->json($this->formatIngredient($ingredient));
            }
            return response()->json(['error' => 'Update error!'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ingredient $ingredients
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingredient $ingredient)
    {
        if ($ingredient = Auth::user()->refrigeratorIngredients->find($ingredient)
            && Auth::user()->refrigeratorIngredients()->detach($ingredient)) {
            return response()->json(['message' => 'Successfully deleted']);
        }
        return response()->json(['error' => 'There is no such ingredient in refrigerator!'], 404);
    }

    protected function getFormatIngredients()
    {
        $ingredients = Auth::user()->refrigeratorIngredients;
        if (!($ingredients->count())) {
            yield ['message' => 'The refrigerator is empty'];
            return;
        }
        foreach ($ingredients as $ingredient) {
            yield $this->formatIngredient($ingredient);
        }
    }

    protected function formatIngredient(Ingredient $ingredient)
    {
        return [
            'id' => $ingredient->id,
            'name' => $ingredient->name,
            'amount' => $ingredient->pivot->amount,
            'created_at' => $ingredient->pivot->created_at->toArray()['formatted'],
            'updated_at' => $ingredient->pivot->updated_at->toArray()['formatted'],
        ];
    }
}