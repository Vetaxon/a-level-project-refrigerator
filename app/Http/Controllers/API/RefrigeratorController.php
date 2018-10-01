<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreRefrigeratorRequest;
use App\Http\Requests\UpdateRefrigeratorRequest;
use App\Ingredient;
use App\Refrigerator;
use App\Http\Controllers\Controller;
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

        if (Refrigerator::create($request->toArray())->save()) {
            return response()->json(["message" => "Saved successfully"], 201);
        }
        return response()->json(["error" => "Save error!"], 500);
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
            return response()->json(['error' => 'Ingredient not found in refrigerator!'], 404);
        }

        $ingredient->pivot->amount = $request->amount;
        if ($ingredient->pivot->save()) {
            return response()->json(['message' => 'Successfully updated']);
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