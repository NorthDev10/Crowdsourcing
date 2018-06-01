<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyProfileRequest extends FormRequest
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
    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'name' => 'required|min:2|max:255',
            'phone' => 'required|max:255',
            'password' => 'nullable|min:8',
            'skills.*.id' => 'numeric',
            'skills.*.name' => 'min:1',
        ];
    }
}
