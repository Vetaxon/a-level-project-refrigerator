<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\WebRecipeRequest;
use App\Ingredient;
use App\Recipe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.recipes.index')
            ->withRecipes(Recipe::getAllRecipesForUser(null)->paginate(3));
    }

    /**
     * Show the form for creating a new recipe.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.recipes.create');
    }

    /**
     * Store a newly created recipe in storage.
     *
     * @param WebRecipeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(WebRecipeRequest $request)
    {
        if ($request->has('picture')) {
            $request->picture = $this->addPicture($request);
        }

        $recipe = Recipe::createRecipe($request);

        return $this->show($recipe);
    }

    /**
     * Display the specified recipe.
     *
     * @param Recipe $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        return view('dashboard.recipes.show')->withRecipe(Recipe::getRecipeByIdForUser($recipe->id));
    }

    /**
     * Show the form for editing the specified recipe.
     *
     * @param Recipe $recipe
     * @return \Illuminate\Http\Response
     */
    public function edit(Recipe $recipe)
    {
        return view('dashboard.recipes.edit')->withRecipe($recipe);
    }

    /**Add an ingredient with amount to the specified recipe
     * @param Recipe $recipe
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addIngredient(Recipe $recipe, Request $request)
    {
        Recipe::storeOneIngredientForRecipe($recipe, $request->all());

        return $this->show($recipe);
    }

    /**Delete the specified ingredient with amount from the specified recipe
     * @param Recipe $recipe
     * @param Ingredient $ingredient
     * @return \Illuminate\Http\Response
     */
    public function deleteIngredient(Recipe $recipe, Ingredient $ingredient)
    {
        $recipe->ingredients()->detach($ingredient);
        return $this->show($recipe);
    }

    /**
     * Update the specified recipe in storage.
     *
     * @param WebRecipeRequest $request
     * @param Recipe $recipe
     * @return \Illuminate\Http\Response
     */
    public function update(WebRecipeRequest $request, Recipe $recipe)
    {
        $recipe->name = $request->name;
        $recipe->text = $request->text;

        if ($request->has('picture')) {
            if ($recipe->picture != null) {
                $this->deletePicture($recipe);
            }
            $recipe->picture = $this->addPicture($request);
        }

        $recipe->save();
        return back()->with('status', "Recipe $recipe->name has been updated");
    }

    /**
     * Remove the specified recipe from storage with old picture.
     *
     * @param Recipe $recipe
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        $this->deletePicture($recipe);
        return back();
    }

    /**
     * Remove the picture of specified recipe
     * return void
     * @param Recipe $recipe
     */
    protected function deletePicture(Recipe $recipe): void
    {
        $server_name = request()->server->get('HTTP_ORIGIN') . '/storage/';
        $picture_store = preg_replace("~$server_name~", '', $recipe->picture);
        Storage::disk('public')->delete($picture_store);
    }

    /**Load a new picture in storage
     * @param Request $request
     * @return string of picture's url
     */
    protected function addPicture(Request $request)
    {
        return asset('storage/' . $request->file('picture')
                ->store('recipes', 'public'));
    }
}
