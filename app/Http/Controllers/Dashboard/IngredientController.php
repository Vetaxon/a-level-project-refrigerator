<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\IngredientRequest;
use App\Ingredient;
use App\Http\Controllers\Controller;

class IngredientController extends Controller
{
    /**
     * Display a listing of the ingredients.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ingredients = Ingredient::where('user_id', null)->paginate(5);
        return view('dashboard.ingredients')->withIngredients($ingredients);
    }


    /**
     * Show the form for creating a new ingredient.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.ingredient_create');
    }

    /**
     * Store a newly created ingredient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IngredientRequest $request)
    {
        $request->merge(['user_id'=>null]);
        $ingredient = new Ingredient($request->toArray());
        $ingredient->save();
        return back()->with('status', "Created new ingredient $ingredient->name");
    }

    /**
     * DISABLED
     * Display the specified ingredient.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * DISABLED
     * Show the form for editing the specified ingredient.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified ingredient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(IngredientRequest $request, Ingredient $ingredient)
    {
        $ingredient->fill($request->toArray())->save();
        return back()->with('status', "Ingredient id= $ingredient->id ($ingredient->name) saved");
    }

    /**
     * Remove the specified ingredient from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ingredient = Ingredient::find($id);
        $ingredient->delete();
        return back()->with('status', "Ingredient id= $id ($ingredient->name) deleted!");
    }
}
