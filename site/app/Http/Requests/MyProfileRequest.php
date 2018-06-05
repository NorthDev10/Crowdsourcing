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
            'skills.*.id' => 'integer',
            'skills.*.name' => 'min:1',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => json_encode(['el' => 'email', 'message' => 'Введите адрес своей электронной почты.']),
            'email.email' => json_encode(['el' => 'email', 'message' => 'Некорректный адрес электронной почты.']),
            'email.max' => json_encode(['el' => 'email', 'message' => 'Длина адреса электронной почты не должна превышать 255 символов']),
            'name.required' => json_encode(['el' => 'name', 'message' => 'Введите своё имя.']),
            'name.min' => json_encode(['el' => 'name', 'message' => 'Имя должно состоять хотя бы из 2-ух символов']),
            'name.max' => json_encode(['el' => 'name', 'message' => 'Имя не должно не должна превышать 255 символов']),
            'phone.required' => json_encode(['el' => 'phone', 'message' => 'Введите номер своего телефона']),
            'phone.max' => json_encode(['el' => 'phone', 'message' => 'Номер телефона не должен превышать 255 символов']),
            'password.min' => json_encode(['el' => 'password', 'message' => 'Пароль должен состоять минимум из 8 символов']),
        ];
    }
}
