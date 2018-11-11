<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11.11.2018
 * Time: 15:41
 */

namespace App\Repositories;


use App\User;

class UserRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function getModel()
    {
        return $this->model;
    }


    /**
     * @return User|\Illuminate\Database\Eloquent\Builder
     */
    public function getAllUsersWithCounts()
    {
        return $this->model->with(['ingredients' => function ($query) {
            return $query->selectRaw('user_id, count(*) as count')->groupBy('user_id');
        }])
            ->with(['recipes' => function ($query) {
                return $query->selectRaw('user_id, count(*) as count')->groupBy('user_id');
            }])
            ->with(['refrigerators' => function ($query) {
                return $query->selectRaw('user_id, count(*) as count')->groupBy('user_id');
            }])
            ->with(['socialites' => function ($query) {
                return $query->selectRaw('*');
            }])
            ->with(['roles' => function ($query) {
                return $query->selectRaw('name');
            }]);
    }

}