<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\IngredientRequest;
use App\Ingredient;
use App\Http\Controllers\Controller;
use App\Repositories\IngredientRepository;
use App\Services\StoreIngredientsForRecipe;

class IngredientController extends Controller
{
    protected $ingredientRepo;

    public function __construct(IngredientRepository $ingredientRepository)
    {
        $this->middleware('role:superadministrator|administrator|moderator', ['except' => ['index', 'show']]);

        $this->ingredientRepo = $ingredientRepository;
    }

    /**
     * Display a listing of the ingredients.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.ingredients.index')
            ->withIngredients($this->ingredientRepo->getAllUsersIngredient(null)->paginate(5));
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
     * @param StoreIngredientsForRecipe $store
     * @return \Illuminate\Http\Response
     */
    public function store(IngredientRequest $request, StoreIngredientsForRecipe $store)
    {
        $ingredient = $store->storeIngredient($request, null);

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
     * @param StoreIngredientsForRecipe $store
     * @return \Illuminate\Http\Response
     */
    public function update(IngredientRequest $request, Ingredient $ingredient, StoreIngredientsForRecipe $store)
    {
        $ingredient->update(['name' => $store->ingredientNameConvertCase($request->name)]);

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
        $ingredient = $this->ingredientRepo->getUsersIngredientById($id);

        $ingredient->delete();

        return back()->with('status', "Ingredient id= $id ($ingredient->name) deleted!");
    }
}
