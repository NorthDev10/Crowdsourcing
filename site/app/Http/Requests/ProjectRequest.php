<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'brand' => 'required|max:50',
            'business_activity.id' => 'required|numeric',
            'business_activity.title' => 'required|max:50',
            'deadline' => 'required|date',
            'project_description' => 'required|min:32',
            'project_name' => 'required|min:2|max:255',
            'subtasks.*.category.id' => 'required|numeric',
            'subtasks.*.category.title' => 'required|max:50',
            'subtasks.*.description' => 'required|min:10',
            'subtasks.*.necessary_skills.*.id' => 'numeric',
            'subtasks.*.necessary_skills.*.name' => 'max:50',
            'subtasks.*.number_executors' => 'required|numeric|min:1',
            'subtasks.*.task_name' => 'required|min:2|max:255',
            'tender_closing' => 'required|numeric|between:0,6',
            'type_project.id' => 'required|numeric',
            'type_project.title' => 'required|min:1|max:50',
        ];
    }
}
