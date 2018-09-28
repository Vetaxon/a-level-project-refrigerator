<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name', 'user_id',
    ];

//    protected $hidden = ['pivot'];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getAllUsersIngredient($parameters)
    {
        $userIngredients = auth()->user()->ingredients()->orderBy('name')->get($parameters);

        $generalIngredients = Ingredient::where('user_id', null)->orderBy('name')->get($parameters);

        return $userIngredients->merge($generalIngredients);
    }

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
