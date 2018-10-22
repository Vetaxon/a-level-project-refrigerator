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
        return view('dashboard.ingredients.index')
            ->withIngredients(Ingredient::getAllUsersIngredient()->paginate(5));
    }


    /**
     * Show the form for creating a new ingredient.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.ingredients.create');
    }

    /**
     * Store a newly created ingredient in storage.
     *
     * @param IngredientRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(IngredientRequest $request)
    {
        $ingredient = Ingredient::create(['name' => mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8")]);

        return back()->with('status', "Created new ingredient $ingredient->name");
    }

    /**
     * DISABLED
     * Display the specified ingredient.
     *
     * @param  int $id
     * @return void
     */
    public function show($id)
    {
    }

    /**
     * DISABLED
     * Show the form for editing the specified ingredient.
     *
     * @param  int $id
     * @return void
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified ingredient in storage.
     *
     * @param IngredientRequest $request
     * @param Ingredient $ingredient
     * @return \Illuminate\Http\Response
     */
    public function update(IngredientRequest $request, Ingredient $ingredient)
    {
        $ingredient->update(['name' => mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8")]);

        return back()->with('status', "Ingredient ($ingredient->name) updated");
    }

    /**
     * Remove the specified ingredient from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ingredient = Ingredient::getUsersIngredientById($id);
        $ingredient->delete();
        return back()->with('status', "Ingredient id= $id ($ingredient->name) deleted!");
    }
}
