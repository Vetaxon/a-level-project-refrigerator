<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 10.11.18
 * Time: 14:16
 */

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface SearchRecipesContract
{
    /**Search recipes for null user
     * @param string $search
     * @return Collection
     */
    public function searchRecipeNullUser(string $search);

    /**Search recipes for specified user
     * @param string $search
     * @param int $user_id
     * @return Collection
     */
    public function searchRecipeForUser(string $search, int $user_id);


}