<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name', 'measure', 'user_id',
    ];

    public function recipes()
    {
        return $this->belongsToMany('App\Recipe', 'recipe_ingredient')->withPivot('value');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
