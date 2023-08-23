<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CategoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|unique:categories,name,'. $this->id,
        ];

        if (!Request::instance()->has('id')) {
            $rules += [
                'status' => 'nullable'
            ];
        } else {
            $rules += [
                'status' => 'required',
            ];
        }

        return $rules;
    }

    // public function messages()
    // {
    //     return [
    //         'required' => ':attribute cannot be empty',
    //     ];
    // }

    // public function attributes()
    // {
    //     return [
    //         'name' => 'Name',
    //         'status' => 'Status',
    //     ];
    // }
}
