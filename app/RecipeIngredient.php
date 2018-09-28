<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RecipeIngredient extends Pivot
{
    protected $fillable = [
        'recipe_id', 'ingredient_id', 'amount',
    ];
}
