<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\IngredientNullRecipeRequest;
use App\Http\Requests\RecipeSearchRequest;
use App\Http\Requests\WebRecipeRequest;
use App\Ingredient;
use App\Recipe;
use App\Repositories\RecipeRepository;
use App\Http\Controllers\Controller;
use App\Services\Contracts\PictureContract;
use App\Services\Contracts\SearchRecipesContract;
use App\Services\StoreIngredientsForRecipe;

class RecipeController extends Controller
{
    protected $recipeRepo;

    public function __construct(RecipeRepository $repository)
    {
        $this->recipeRepo = $repository;
    }

    /**
     * Display a listing of recipes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.recipes.index')
            ->withRecipes($this->recipeRepo->getAllRecipesForUser(null)->paginate(5));
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
     * @param PictureContract $pictureContract
     * @return \Illuminate\Http\Response
     * @internal param Recipe $recipe
     */
    public function store(WebRecipeRequest $request, PictureContract $pictureContract)
    {
        $recipeAttributes = $request->only(['name', 'text']);

        $recipeAttributes['picture'] = $request->file('picture') ? $pictureContract->save($request->file('picture')) : null;

        $this->recipeRepo->getModel()->fill($recipeAttributes)->save();

        return $this->show($this->recipeRepo->getModel());
    }

    /**
     * Display the specified recipe.
     *
     * @param Recipe $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        return view('dashboard.recipes.show')->withRecipe($this->recipeRepo->getRecipeByIdForUser($recipe->id));
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
     * @param IngredientNullRecipeRequest $request
     * @param StoreIngredientsForRecipe $store
     * @return \Illuminate\Http\Response
     */
    public function addIngredient(Recipe $recipe, IngredientNullRecipeRequest $request, StoreIngredientsForRecipe $store)
    {
        $store->storeOneIngredientForRecipe($recipe, $request->all());
        $recipe->save();

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
        $recipe->save();
        return $this->show($recipe);
    }

    /**
     * Update the specified recipe in storage.
     *
     * @param WebRecipeRequest $request
     * @param Recipe $recipe
     * @param PictureContract $pictureContract
     * @return \Illuminate\Http\Response
     */
    public function update(WebRecipeRequest $request, Recipe $recipe, PictureContract $pictureContract)
    {
        if ($file = $request->file('picture')) {
            if ($recipe->picture != null) {
                $pictureContract->delete($recipe->picture);
            }
            $recipe->picture = $pictureContract->save($file);
        }

        $recipe->fill($request->only('name', 'text'))->save();

        return back()->with('status', "Recipe $recipe->name has been updated");
    }

    /**
     * Remove the specified recipe from storage with old picture.
     *
     * @param Recipe $recipe
     * @param PictureContract $pictureContract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe, PictureContract $pictureContract)
    {
        $recipe->delete();
        $pictureContract->delete($recipe->picture);
        return back();
    }

    /**
     * @param RecipeSearchRequest $request
     */
    public function searchRecipe(RecipeSearchRequest $request, SearchRecipesContract $searchRecipes)
    {
        return view('dashboard.recipes.index')
            ->withRecipes($searchRecipes->searchRecipeNullUser($request->search))
            ->withPaginate(false);
    }
    
    

}
