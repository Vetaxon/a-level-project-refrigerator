<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

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

    public static function getAllIngredientsForUser()
    {
        return DB::table('ingredients')
            ->select('ingredients.id',
                'ingredients.name as ingredient',
                'measures.name as measure',
                'ingredients.user_id',
                'ingredients.created_at',
                'ingredients.updated_at')
            ->join('measures', 'ingredients.measure_id', '=', 'measures.id')
            ->where('user_id', auth()->user()->id)
            ->orWhere('user_id', null)
            ->orderBy('user_id', 'desc')
            ->orderBy('ingredient')
            ->get();
    }

    public static function getIngredientByIdForUser($id)
    {
        return DB::table('ingredients')
            ->select('ingredients.id',
                'ingredients.name as ingredient',
                'measures.name as measure',
                'ingredients.user_id',
                'ingredients.created_at',
                'ingredients.updated_at')
            ->join('measures', 'ingredients.measure_id', '=', 'measures.id')
            ->where([['user_id', auth()->user()->id], ['ingredients.id', $id]])
            ->orWhere([['user_id', null], ['ingredients.id', $id]])
            ->get();
    }


}
