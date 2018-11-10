<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 10.11.18
 * Time: 14:21
 */

namespace App\Services;

use App\ElasticSearch\Rules\RecipeSearchRuleFirst;
use App\ElasticSearch\Rules\RecipeSearchRuleForUser;
use App\ElasticSearch\Rules\RecipeSearchRuleSecond;
use App\ElasticSearch\Rules\RecipeSearchRuleThird;
use App\Repositories\RecipeRepository;
use App\Services\Contracts\SearchRecipesContract;
use Illuminate\Database\Eloquent\Collection;

class SearchRecipes implements SearchRecipesContract
{
    protected $recipeRepository;
    
    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**Search recipes for null user
     * @param string $search
     * @return Collection
     */
    public function searchRecipeNullUser(string $search)
    {
        $recipe = $this->recipeRepository->searchRecipesUserNull($search, RecipeSearchRuleFirst::class);

        if ($recipe->isNotEmpty()) {
            return $recipe;
        }

        $recipe = $this->recipeRepository->searchRecipesUserNull($search, RecipeSearchRuleSecond::class);

        if ($recipe->isNotEmpty()) {
            return $recipe;
        }

        return $this->recipeRepository->searchRecipesUserNull($search, RecipeSearchRuleThird::class);
    }

    /**Search recipes for specified user
     * @param string $search
     * @param int $user_id
     * @return Collection
     */
    public function searchRecipeForUser(string $search, int $user_id)
    {
        $rule = RecipeSearchRuleForUser::class;

        $recipes = $this->recipeRepository->searchRecipeUserId($search, $rule, $user_id);

        return $recipes->count() > 5 ? $recipes : $recipes->merge($this->recipeRepository->searchRecipesUserNull($search, $rule));
    }
}