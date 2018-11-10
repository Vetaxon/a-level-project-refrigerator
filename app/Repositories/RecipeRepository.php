<?php

namespace App\Repositories;

use App\Recipe;

class RecipeRepository
{
    protected $model;
    
    protected $selectParams = ['id', 'name', 'text', 'picture', 'user_id'];
    
    protected $selectIngredients = ['id', 'name', 'amount'];

    public function __construct()
    {
        $this->model = new Recipe();
    }
    
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * @return Recipe|\Illuminate\Database\Eloquent\Builder
     */
    public function getAllRecipesForUser($user_id = null)
    {
        return $this->model->with([
            'ingredients' => function ($query) {
                return $query->select($this->selectIngredients);
            }])
            ->select($this->selectParams)
            ->where('recipes.user_id', null)
            ->orWhere('recipes.user_id', $user_id)
            ->orderByDesc('created_at');
    }

    /**Get a recipe by id if it is available for user
     *
     * @param $id
     * @param null $user_id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getRecipeByIdForUser($id, $user_id = null)
    {
        return $this->model->with([
            'ingredients' => function ($query) {
                return $query->select($this->selectIngredients);
            }])
            ->select($this->selectParams)
            ->where('id', $id)
            ->where(function ($query) use ($user_id) {
                return $query->whereNull('user_id')
                    ->orWhere('user_id', $user_id);
            })->first();
    }

    /**Get recipes by multiple ids
     * @param $ids
     * @return Recipe|\Illuminate\Database\Eloquent\Builder
     */
    public function getRecipesByMultipleIds($ids)
    {
        return $this->model->with([
            'ingredients' => function ($query) {
                return $query->select($this->selectIngredients);
            }])
            ->select($this->selectParams)
            ->whereIn('recipes.id', $ids);
    }

    /**
     * @param $user_id
     * @return Recipe|\Illuminate\Database\Eloquent\Builder
     */
    public function getRecipeWithIngredientsByUser($user_id)
    {
        return $this->model->with([
            'ingredients' => function ($query) {
                return $query->select($this->selectIngredients);
            }])
            ->select($this->selectParams)
            ->where('recipes.user_id', $user_id);
    }

    /**
     * @param string $search
     * @param $searchRuleClass
     * @return \Illuminate\Support\Collection
     */
    public function searchRecipesUserNull(string $search, $searchRuleClass)
    {
        return $this->model->search($search)->with([
            'ingredients' => function ($query) {
                return $query->select($this->selectIngredients);
            }])
            ->rule($searchRuleClass)
            ->whereNotExists('user_id')
            ->get();
    }

    /**
     * @param string $search
     * @param $searchRuleClass
     * @param $user_id
     * @return \Illuminate\Support\Collection
     */
    public function searchRecipeUserId(string $search, $searchRuleClass, $user_id)
    {
        return $this->model->search($search)->with([
            'ingredients' => function ($query) {
                return $query->select($this->selectIngredients);
            }])
            ->rule($searchRuleClass)
            ->where('user_id', $user_id)
            ->get();
    }

}