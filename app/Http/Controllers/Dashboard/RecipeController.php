<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\IngredientNullRecipeRequest;
use App\Http\Requests\WebRecipeRequest;
use App\Ingredient;
use App\Recipe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class RecipeController extends Controller
{

    protected $pathStorePictures = 'app/public/recipes/';

    protected $pathPublicPictures = 'storage/recipes/';

    protected $pictureFitSize = 380;

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
        if ($file = $request->file('picture')) {
            $request->picture = $this->addPictureIntervention($file);
        }

        return $this->show(Recipe::createRecipe($request));
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
     * @param IngredientNullRecipeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function addIngredient(Recipe $recipe, IngredientNullRecipeRequest $request)
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

        if ($file = $request->file('picture')) {
            if ($recipe->picture != null) {
                $this->deletePicture($recipe);
            }
            $recipe->picture = $this->addPictureIntervention($file);
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
    protected function deletePicture(Recipe $recipe)
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

    /**Load a new picture in storage within fit throw Intervention
     * @param $file
     * @return string
     */
    protected function addPictureIntervention($file)
    {
        $filename = str_random(30) . '.' . $file->getClientOriginalExtension();

        if (!file_exists($this->getStoragePicturePath()))
            mkdir($this->getStoragePicturePath(), 0777, true);

        Image::make($file)
            ->fit($this->pictureFitSize, $this->pictureFitSize)
            ->save($this->getStoragePicturePath() . $filename);

        return asset($this->pathPublicPictures . $filename);
    }

    /**
     * @return string
     */
    protected function getStoragePicturePath()
    {
        return storage_path($this->pathStorePictures);
    }
}
