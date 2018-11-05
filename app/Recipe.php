<?php

namespace App;

use App\ElasticSearch\Index\RefrigeratorIndexConfigurator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use ScoutElastic\Searchable;

class Recipe extends Model
{
    use Searchable;

    protected $indexConfigurator = RefrigeratorIndexConfigurator::class;

    protected $searchRules = [
        //
    ];
    protected $mapping = [
        'properties' => [
            'name' => [
                'type' => 'string',
                'analyzer' => 'russian',
                'boost' => 3,
            ],
            'text' => [
                'type' => 'string',
                'analyzer' => 'russian',
                'boost' => 1,
            ],
            'user_id' => [
                'type' => 'string',
                'index' => 'not_analyzed',
            ],
            'picture' => [
                'type' => 'string',
                'index' => 'not_analyzed',
            ],
            'ingredients' => [
                'type' => 'string'
            ]
        ]
    ];

//    public function toSearchableArray()
//    {
//        $array = $this->with([
//            'ingredients' => function ($query) {                
//                return $query->select(['id', 'name', 'amount']);
//            }])
//            ->get()
//            ->map(function ($iterable){
//                if($iterable-)
//            });
//        
//        info($array);
//
//        return $array;
//    }

    protected $fillable = [
        'name', 'text', 'user_id', 'picture'
    ];

    protected static $selectParams = ['id', 'name', 'text', 'picture', 'user_id'];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function recipeIngredients()
    {
        return $this->hasMany('App\RecipeIngredient');
    }

    public function ingredients()
    {
        return $this->belongsToMany('App\Ingredient', 'recipe_ingredient')
            ->withPivot('amount');

    }

    /**
     * @return Recipe|\Illuminate\Database\Eloquent\Builder
     */
    public static function getAllRecipesForUser($user_id = null)
    {
        return self::with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->select(self::$selectParams)
            ->where('recipes.user_id', null)
            ->orWhere('recipes.user_id', $user_id)
            ->orderByDesc('created_at');
    }

    /**Get a recipe by id if it is available for user
     *
     * @param $id
     * @return Recipe|Recipe[]|bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public static function getRecipeByIdForUser($id, $user_id = null)
    {
        return self::with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->select(self::$selectParams)
            ->where('id', $id)
            ->where(function ($query) use ($user_id) {
                return $query->whereNull('user_id')
                    ->orWhere('user_id', $user_id);
            })->first();
    }


    /**
     *
     * Store ingredients for recipes in recipe_ingredient table after storing new ingredients for user if does not exists
     * @param $recipe
     * @param $ingredients
     * @return bool
     */
    public static function storeIngredientsForRecipe($recipe, $ingredients, $user_id = null)
    {
        foreach ($ingredients as $ingredient) {
            $validIngredient['name'] = mb_convert_case(key($ingredient), MB_CASE_TITLE, "UTF-8");;
            $validIngredient['amount'] = array_values($ingredient)[0];
            self::storeOneIngredientForRecipe($recipe, $validIngredient, $user_id);
        }
        return true;
    }

    /**
     * @param $recipe
     * @param $ingredient
     * @param null $user_id
     * @return void
     */
    public static function storeOneIngredientForRecipe($recipe, $ingredient, $user_id = null)
    {
        $ingredient['name'] = mb_convert_case($ingredient['name'], MB_CASE_TITLE, "UTF-8");
        $validatorExistsName = self::validateIngredientsExists($ingredient, $user_id);

        //Create a new ingredient if it does not exist for user and put it in recipe
        if ($validatorExistsName->fails()) {

            $ingredient['user_id'] = $user_id;
            $newIngredient = Ingredient::create($ingredient);
            $recipe->ingredients()->attach([$newIngredient->id => ['amount' => $ingredient['amount']]]);

        } else { // If an ingredient is available for user put it in a recipe

            $ingredientExistingId = Ingredient::getIngredientIdByName($ingredient, $user_id);
            $recipe->ingredients()->attach([$ingredientExistingId => ['amount' => $ingredient['amount']]]);
        }
    }

    /**Get recipes by multiple ids
     * @param $ids
     * @return Recipe|\Illuminate\Database\Eloquent\Builder
     */
    public static function getRecipesByMultipleIds($ids)
    {
        return self::with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->select(self::$selectParams)
            ->whereIn('recipes.id', $ids);
    }

    /**
     * Validate ingredient if exist in ingredients and available for user
     * @param $ingredient
     * @return mixed
     */
    protected static function validateIngredientsExists($ingredient, $user_id = null)
    {
        return Validator::make($ingredient, [
            'name' => [
                Rule::exists('ingredients')->where(function ($query) use ($user_id) {
                    $query->where('user_id', $user_id)->orWhere('user_id', null);
                }),
            ]
        ]);
    }

    /**
     * @param $request
     * @param $user_id
     * @return mixed
     */
    public static function createRecipe($request, $user_id = null)
    {
        return self::create([
            'name' => $request->name,
            'text' => $request->text,
            'picture' => $request->picture,
            'user_id' => $user_id,
        ]);
    }

    /**
     * @param $user_id
     * @return Recipe|\Illuminate\Database\Eloquent\Builder
     */
    public static function getRecipeWithIngredientsByUser($user_id)
    {
        return self::with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->select(self::$selectParams)
            ->where('recipes.user_id', $user_id);
    }


}
