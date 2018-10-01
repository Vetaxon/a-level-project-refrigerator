<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Refrigerator extends Pivot
{
    protected $table = 'refrigerators';

    protected $fillable = [
        'user_id', 'ingredient_id', 'amount',
    ];
}