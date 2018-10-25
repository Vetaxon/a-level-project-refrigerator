<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAuthRequest extends FormRequest
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
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {

        if ($request->route()->getName() == 'change.password') {
            return [
                'old_password' => 'required|string|min:5',
                'password' => 'string|min:5|confirmed',
            ];
        }


        return [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
        ];
    }
}
