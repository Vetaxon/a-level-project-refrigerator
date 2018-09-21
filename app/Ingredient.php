<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name', 'measure_id', 'user_id',
    ];

    public function measure()
    {
        return $this->belongsTo('App\Measure');
    }

    public function recipes()
    {
        return $this->belongsToMany('App\Recipe', 'recipe_ingredient')->withPivot('value');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
