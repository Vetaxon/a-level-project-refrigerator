<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name', 'user_id',
    ];

    protected $hidden = [
        'pivot'
    ];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Return all ingredients available for user
     * @param $parameters of attributes to return
     * @return mixed
     */
    public static function getAllUsersIngredient($parameters)
    {
        $userIngredients = auth()->user()->ingredients()->orderBy('name')->get($parameters);

        $generalIngredients = Ingredient::where('user_id', null)->orderBy('name')->get($parameters);

        return $userIngredients->merge($generalIngredients);
    }

    /**
     * Return ingredient by id of it is available for user
     * @param $id
     * @return bool|\Illuminate\Support\Collection
     */
    public static function getUsersIngredientById($id)
    {
        $userIngredients = collect(auth()->user()->ingredients()->find($id));

        if(!$userIngredients->isEmpty()){

            return $userIngredients;
        }

        $generalIngredients = collect(Ingredient::where('user_id', null)->where('id', $id)->get());

        if(!$generalIngredients->isEmpty()){
            return $generalIngredients;
        }

        return false;

    }

}
