<?php

namespace App\Repositories;

use App\ElasticSearch\Rules\RecipeSearchRuleFirst;
use App\ElasticSearch\Rules\RecipeSearchRuleForUser;
use App\ElasticSearch\Rules\RecipeSearchRuleSecond;
use App\ElasticSearch\Rules\RecipeSearchRuleThird;
use App\Recipe;
use App\ElasticSearch\Rules\RecipeSearchRule;

class RecipeRepository
{
    public static function searchRecipeNullUser(string $search)
    {
        $recipe = Recipe::search($search)->with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->rule(RecipeSearchRuleFirst::class)
            ->whereNotExists('user_id')
            ->get();

        if(collect($recipe)->isNotEmpty()){
            return $recipe;
        }

        $recipe = Recipe::search($search)->with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->rule(RecipeSearchRuleSecond::class)
            ->whereNotExists('user_id')
            ->get();

        if(collect($recipe)->isNotEmpty()){
            return $recipe;
        }

        $recipe = Recipe::search($search)->with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->rule(RecipeSearchRuleThird::class)
            ->whereNotExists('user_id')
            ->get();

        return $recipe;
    }

    public static function searchRecipeForUser(string $search, int $user_id)
    {
        $recipe_user =  collect(Recipe::search($search)->with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->rule(RecipeSearchRuleForUser::class)
            ->where('user_id', $user_id)
            ->get());

        if($recipe_user->count() > 5){
            return $recipe_user;
        }

        $recipe_null =  Recipe::search($search)->with([
            'ingredients' => function ($query) {
                return $query->select(['id', 'name', 'amount']);
            }])
            ->rule(RecipeSearchRuleForUser::class)
            ->whereNotExists('user_id')
            ->get();
        
        return $recipe_null;
        
    }
    
    
}