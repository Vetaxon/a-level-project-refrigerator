<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    protected $fillable = [
        'name',
    ];

    public function ingredients()
    {
        return $this->hasMany('App\Ingredient');
    }
}
