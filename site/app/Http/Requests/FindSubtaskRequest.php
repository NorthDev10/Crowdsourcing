<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FindSubtaskRequest extends FormRequest
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
            'q' => 'required|min:1|max:45'
        ];
    }
    
    public function messages()
    {
        return [
            'q.required' => 'Поисковой запрос должен состоять хотя бы из одного слова!',
            'q.min' => 'Поисковой запрос должен состоять хотя бы из одного слова!',
            'q.max' => 'Количество символов в поисковом запросе, не может превышать 45.',
        ];
    }
}
