<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserStoreRequest extends FormRequest
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
        if ($request->isMethod('post')) {
            return [
                'name' => 'required|string|max:255|min:3',
                'email' => 'required|string|email|max:255|unique:users',
            ];
        }

        if ($request->isMethod('put')) {

            if ($request->has('name')) {
                return [
                    'name' => 'string|max:255|min:3'
                ];
            }

            if ($request->has('email')) {
                return [
                    'email' => 'string|email|max:255|unique:users',
                ];
            }
        }
    }
}
