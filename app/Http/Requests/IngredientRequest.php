<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        /**
         * Rules for store a new ingredient by user.
         *
         */

        if ($request->isMethod('post')) {
            return [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('ingredients')->where(function ($query) {
                        $query->where('user_id', auth()->id())->orWhere('user_id', null);
                    }),
                ]
            ];
        }

        /**
         * Rules for update a new ingredient by user.
         *
         */

        if ($request->isMethod('put')) {

            return [
                'name' => [
                    'string',
                    'max:255',
                    Rule::unique('ingredients')->where(function ($query) {
                        $query->where('user_id', auth()->id())->orWhere('user_id', null);
                    }),
                ]
            ];
        }

    }
}
