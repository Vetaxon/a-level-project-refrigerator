<?php

namespace App\Http\Requests;

use App\Rules\AdminRoleAssign;
use App\Rules\ModeratorRoleAssign;
use App\Rules\SuperAdminRoleAssign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRolesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {

        if ($request->isMethod('put')) {
            return [
                'roleId' => [
                    'required',
                    'array',
                    new SuperAdminRoleAssign,
                    new AdminRoleAssign,
                ]
            ];
        }
    }
}