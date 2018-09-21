<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'name', 'text', 'user_id',
    ];

    public function ingredients()
    {
        return $this->belongsToMany('App\Ingredient', 'recipe_ingredient')->withPivot('value');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
