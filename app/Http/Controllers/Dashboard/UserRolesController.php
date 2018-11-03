<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\UserRolesRequest;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.userRoles')->with(['users' => User::All(), 'roles' => Role::All()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRolesRequest $request, User $user)
    {
        $status = [];
        $roles = Role::all();
        foreach ($request->roleId as $roleId => $value) {
            if ($value && !($user->hasRole($roles[$roleId - 1]->name))) {
                $user->attachRole($roleId);
                $status[] = 'User ' . $user->name . ' got the ' . $roles[$roleId - 1]->name . ' role!';
            }
            if (!$value && ($user->hasRole($roles[$roleId - 1]->name))) {
                $user->detachRole($roleId);
                $status[] = 'User ' . $user->name . ' lost the ' . $roles[$roleId - 1]->name . ' role!';
            }
        }
        return back()->withStatus($status);
    }
}
