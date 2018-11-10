<?php

namespace App;

use App\ElasticSearch\Index\RefrigeratorIndexConfigurator;
use Illuminate\Database\Eloquent\Model;
use ScoutElastic\Searchable;

class Recipe extends Model
{
    use Searchable;

    protected $indexConfigurator = RefrigeratorIndexConfigurator::class;

    protected $mapping = [
        'properties' => [
            'name' => [
                'type' => 'string',
                'analyzer' => 'russian',
                'boost' => 3,
            ],
            'text' => [
                'type' => 'string',
                'analyzer' => 'russian',
                'boost' => 1,
            ],
            'user_id' => [
                'type' => 'integer'
            ],
            'ingredients' => [
                "type" => "string",
            ]
        ]
    ];

    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['ingredients'] = collect($this->ingredients()->select('name')->get()->toArray())->implode("name", ", ");
        return $array;
    }

    protected $fillable = [
        'name', 'text', 'user_id', 'picture'
    ];

    protected $hidden = [];

    protected static $selectParams = ['id', 'name', 'text', 'picture', 'user_id'];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function recipeIngredients()
    {
        return $this->hasMany('App\RecipeIngredient');
    }

    public function ingredients()
    {
        return $this->belongsToMany('App\Ingredient', 'recipe_ingredient')
            ->withPivot('amount');

    }
}
