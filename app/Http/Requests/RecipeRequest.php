<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class RecipeRequest extends FormRequest
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

        if ($request->isMethod('post')) {
            return [
                'name' => 'required|string|max:255',
                'text' => 'required|string|max:2550',
                'ingredients' => 'required|array'
            ];
        }

        if ($request->isMethod('put')) {
            return [
                'name' => 'string|max:255',
                'text' => 'string|max:2550',
                'ingredients' => 'array'
            ];
        }


    }
}
