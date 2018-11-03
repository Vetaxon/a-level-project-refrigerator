<?php

namespace App\Rules;

use App\Role;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminRoleAssign implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $authUser;
    protected $adminRoleName;
    protected $userSubject;
    protected $roleSuperUser;
    protected $superUserRoleName;

    public function __construct()
    {
        $this->authUser = Auth::user();
        $this->adminRoleName = 'administrator';
        $this->userSubject = Route::getCurrentRoute()->user;
        $this->roleSuperUser = Role::where('name', $this->adminRoleName)->first();
        $this->superUserRoleName = config('laratrust_seeder.non_removable_role.role_name');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $ruleNumbers)
    {
        if($this->userSubject->hasRole($this->adminRoleName) && !$this->authUser->hasRole($this->superUserRoleName)){
            return false;
        }

        return true;
//        return !($this->userSubject->hasRole($this->adminRoleName) && !($this->authUser->hasRole($this->superUserRoleName)));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Admin role error!';
    }
}