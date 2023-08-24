<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BatchRequest extends FormRequest
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
            'medicine_id' => 'required',
            'quantity' => 'required',
            'expired_date' => 'required|date'
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

    public function attributes()
    {
        return [
            'medicine_id' => 'medicine name',
        ];
    }
}
