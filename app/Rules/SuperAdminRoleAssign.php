<?php

namespace App\Rules;

use App\Role;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SuperAdminRoleAssign implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $authUser;
    protected $superUserRoleName;
    protected $userSubject;
    protected $roleSuperUser;

    public function __construct()
    {
        $this->authUser = Auth::user();
        $this->superUserRoleName = config('laratrust_seeder.non_removable_role.role_name');
        $this->userSubject = Route::getCurrentRoute()->user;
        $this->roleSuperUser = Role::where('name', $this->superUserRoleName)->first();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $ruleNumbers)
    {
        //If user-subject isn't superuser and anybody tries assign superuser role for him, permission will be denied
        if (!$this->userSubject->hasRole($this->superUserRoleName) && ($ruleNumbers[$this->roleSuperUser->id])) {
            return false;
        }

        //If anybody tries to take away superuser role, permission will be denied
        if($this->userSubject->hasRole($this->superUserRoleName ) && (null === $ruleNumbers[$this->roleSuperUser->id])){
            return false;
        }

        //If authenticated user has not role superuser and he tries to change superuser roles, permission will be denied
        if($this->userSubject->hasRole($this->superUserRoleName) && !$this->authUser->hasRole($this->superUserRoleName)){
            return false;
        }

        // In other cases permission will be given:
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Superadmin role error!';
    }
}