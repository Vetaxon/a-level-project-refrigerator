<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11.11.2018
 * Time: 19:15
 */

namespace App\Repositories;


use App\Ingredient;

class IngredientRepository
{
    protected $model;

    public function __construct(Ingredient $ingredient)
    {
        $this->model = $ingredient;
    }

    /**
     * @param null $user_id
     * @return mixed
     */
    public function getAllUsersIngredient($user_id = null)
    {
        return $this->model->where('user_id', null)
            ->orWhere('user_id', $user_id)
            ->orderByDesc('created_at');
    }

    /**
     * Return ingredient by id of it is available for user
     * @param $id
     * @return bool|\Illuminate\Support\Collection
     */
    public function getUsersIngredientById($id, $user_id = null)
    {
        return $this->model->where('id', $id)
            ->where(function ($query) use ($user_id) {
                return $query->whereNull('user_id')
                    ->orWhere('user_id', $user_id);
            })->first();
    }

}