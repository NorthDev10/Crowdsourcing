<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'user_id' => 'integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
            'description' => 'required|min:5',
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'Пользователь с таким идентификатором не найден!',
            'project_id.exists' => 'Проект с таким идентификатором не найден!',
            'description.required' => 'Введите свой отзыв!',
            'description.min' => 'Отзыв должен быть не менее 5 символов',
        ];
    }
}
