<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refrigerator extends Model
{
    protected $fillable = [
        'user_id', 'ingredient_id', 'amount',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
