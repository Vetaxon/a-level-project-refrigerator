<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name', 'user_id',
    ];

    protected $hidden = [
        'pivot',
    ];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Return all ingredients available for user
     * @return mixed
     */
    public function recipes()
    {
        return $this->belongsToMany('App\Recipe', 'recipe_ingredient')
            ->withPivot('amount')
            ->withPivot('created_at')
            ->withPivot('updated_at');
    }

    /**
     * @param null $user_id
     * @return mixed
     */
    public static function getAllUsersIngredient($user_id = null)
    {
        return self::where('user_id', null)
            ->orWhere('user_id', $user_id)
            ->orderByDesc('created_at');
    }

    /**
     * Return ingredient by id of it is available for user
     * @param $id
     * @return bool|\Illuminate\Support\Collection
     */
    public static function getUsersIngredientById($id)
    {
        $userIngredients = collect(auth()->user()->ingredients()->find($id));

        if (!$userIngredients->isEmpty()) {

            return $userIngredients;
        }

        $generalIngredients = collect(Ingredient::where('user_id', null)->where('id', $id)->get());

        if (!$generalIngredients->isEmpty()) {
            return $generalIngredients;
        }

        return false;

    }


    /**Get Ingredient id by name for user
     * @param $ingredient
     * @return mixed
     */
    public static function getIngredientIdByName($ingredient, $user_id = null)
    {
        return self::where('name', $ingredient['name'])
            ->where(function ($query) use ($user_id) {
                return $query->whereNull('user_id')
                    ->orWhere('user_id', $user_id);
            })->first()->id;
    }
}
