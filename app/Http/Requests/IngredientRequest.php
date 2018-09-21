<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class IngredientRequest extends FormRequest
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
                'name' => 'required|string|max:255|unique:ingredients,name',
                'measure_id' => 'required|integer|exists:measures,id',
            ];
        }

        if ($request->isMethod('put') and $request->has('name')) {

            return [
                'name' => 'string|max:255|unique:ingredients,name',
                'measure_id' => 'integer|exists:measures,id',
            ];
        }

        if ($request->isMethod('put') and !$request->has('name')) {

            return [
                'measure_id' => 'integer|exists:measures,id',
            ];
        }

    }
}
