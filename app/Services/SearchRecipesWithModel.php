<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 11.11.18
 * Time: 13:30
 */

namespace App\Services;

use App\Repositories\RecipeRepository;
use App\Services\Contracts\SearchRecipesContract;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class SearchRecipesWithModel implements SearchRecipesContract
{

    /**Search recipes for null user
     * @param string $search
     * @return Collection
     */
    public function searchRecipeNullUser(string $search)
    {
        // TODO: Implement searchRecipeNullUser() method.
    }

    /**Search recipes for specified user
     * @param User $user
     * @return Collection
     */
    public function searchRecipeForUser(User $user)
    {
        $recipeRepo = new RecipeRepository();
        
        $refrigerator = $user->refrigeratorIngredients()->get()->toArray();
        
        $recipes = $recipeRepo->getAllRecipesForUser($user->id)->get()->toArray();

        $recommendedRecipesIds = $this->getRecommendedRecipesIds($recipes, $refrigerator);
        
        return $recipeRepo->getRecipesByMultipleIds($recommendedRecipesIds);
        
    }

    /**
     * Get get id's array of all recommended recipes for user due to ingredients in refrigerator
     * @param $recipes - all recipes available for user
     * @param $refrigerator - user's ingredients in a refrigerator
     * @return array
     */
    protected function getRecommendedRecipesIds($recipes, $refrigerator)
    {
        $recommendedRecipesIds = [];

        foreach ($recipes as $recipe) {

            $recipeIngredientCount = count($recipe['ingredients']);
            $ingredientMatches = 0;

            foreach ($recipe['ingredients'] as $recipeIngredient) {
                
                foreach ($refrigerator as $refrigeratorIngredient) {
                    
                    if ($recipeIngredient['id'] == $refrigeratorIngredient['id']) {
                        $ingredientMatches++;
                    }
                }
            }

            if ($recipeIngredientCount == $ingredientMatches) {
                $recommendedRecipesIds[] = $recipe['id'];
            }
        }

        return $recommendedRecipesIds;
    }
}