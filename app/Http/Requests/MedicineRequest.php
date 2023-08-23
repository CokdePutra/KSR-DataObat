<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class MedicineRequest extends FormRequest
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
            'category_id' => 'required',
            'name' => 'required|unique:medicines,name,' . $this->id,
            // 'stock' => 'required|numeric',
            'unit' => 'required',
            'other_unit' => 'required_if:unit,Lainnya' ,
            'medicine_code' => 'required'
        ];

        if (!Request::instance()->has('id')) {
            $rules += [
                'status' => 'nullable',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ];
        } else {
            $rules += [
                'status' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required_if' => 'The :attribute field is required.',
        ];
    }

    public function attributes()
    {
        return [
            'other_unit' => 'Unit',
        ];
    }
}
