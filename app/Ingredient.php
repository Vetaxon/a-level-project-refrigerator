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
