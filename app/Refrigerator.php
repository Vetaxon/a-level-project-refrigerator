<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Refrigerator extends Pivot
{
    protected $table = 'refrigerators';

    protected $fillable = [
        'user_id', 'ingredient_id', 'amount',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function ingredients()
    {
        return $this->hasOne('App\Ingredient');
    }
}