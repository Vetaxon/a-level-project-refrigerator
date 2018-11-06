<?php

namespace App\Repositories;

use App\Recipe;
use App\ElasticSearch\Rules\RecipeSearchRule;

class RecipeRepository
{
    public static function searchRecipeNullUser(string $search)
    {
        return Recipe::search($search)->with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->rule(RecipeSearchRule::class)
            ->whereNotExists('user_id')
            ->get();
    }
}