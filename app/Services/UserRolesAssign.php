<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 18.11.18
 * Time: 19:41
 */

namespace App\Services;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class UserRolesAssign
{
    public static function userCanBeModified(){
        Blade::if('userCanBeModified', function ($editableUser) {
            return (!$editableUser->hasRole('administrator|superadministrator') || Auth::user()->hasRole('superadministrator')) &&
                !(Auth::user()->hasRole('moderator') && !Auth::user()->hasRole('administrator|superadministrator'));
        });
    }
    public static function printNotEditableRole(){
        Blade::if('printNotEditableRole', function ($editableUser, $role) {
            return $editableUser->hasRole($role->name);
        });
    }
    public static function superadminHidden(){
        Blade::if('superadminHidden', function ($role, $editableUser) {
            return $role->disabled && $editableUser->hasRole('superadministrator');
        });
    }
    public static function disabledRole(){
        Blade::if('disabledRole', function ($role) {
            return $role->disabled || ($role->name === 'administrator' && !Auth::user()->hasRole('superadministrator'));
        });
    }
    public static function checkedRole(){
        Blade::if('checkedRole', function ($editableUser, $role) {
            return $editableUser->hasRole($role->name);
        });
    }
}