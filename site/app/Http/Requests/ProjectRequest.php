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
            'business_activity.title' => 'required|max:50',
            'deadline' => 'required|date',
            'project_description' => 'required|min:32',
            'project_name' => 'required|min:2|max:255',
            'subtasks.*.category.title' => 'required|max:50',
            'subtasks.*.description' => 'required|min:10',
            'subtasks.*.necessary_skills.*.name' => 'max:50',
            'subtasks.*.number_executors' => 'required|numeric|min:1|max:10',
            'subtasks.*.task_name' => 'required|min:2|max:255',
            'tender_closing' => 'required|numeric|between:0,6',
            'type_project.title' => 'required|min:1|max:50',
        ];
    }

    public function messages()
    {
        return [
            'brand.required' => json_encode(['el' => 'brand', 'message' => 'Введите название компании!']),
            'brand.max' => json_encode(['el' => 'brand', 'message' => 'Название компании не должно превышать 50 символов!']),
            'business_activity.title.required' => json_encode(['el' => 'businessActivity', 'message' => 'Введите свою сферу деятельности!']),
            'business_activity.title.max' => json_encode(['el' => 'businessActivity', 'message' => 'Сфера деятельности недолжна превышать 50 символов!']),
            'deadline.required' => json_encode(['el' => 'deadline', 'message' => 'Введите крайний срок сдачи проекта']),
            'deadline.date' => json_encode(['el' => 'deadline', 'message' => 'Крайний срок должен быть следующем формате: YYYY-MM-DD']),
            'project_description.required' => json_encode(['el' => 'projectDescription', 'message' => 'Введите описание проекта']),
            'project_description.min' => json_encode(['el' => 'projectDescription', 'message' => 'Описание проекта должно быть не меньше 32 символов']),
            'project_name.required' => json_encode(['el' => 'projectName', 'message' => 'Введите название проекта']),
            'project_name.min' => json_encode(['el' => 'projectName', 'message' => 'Название проекта должно быть не меньше 2 символа']),
            'project_name.max' => json_encode(['el' => 'projectName', 'message' => 'Название проекта должно быть не более 255 символов']),
            'subtasks.*.category.title.required' => json_encode(['el' => 'subtasksCategory', 'message' => 'Выберите категорию задачи']),
            'subtasks.*.category.title.max' => json_encode(['el' => 'subtasksCategory', 'message' => 'Название категории задачи не должна превышать 50 символов']),
            'subtasks.*.description.required' => json_encode(['el' => 'subtasksDescription', 'message' => 'Введите описание задачи']),
            'subtasks.*.description.min' => json_encode(['el' => 'subtasksDescription', 'message' => 'Описание задачи должно быть не менее 10 символов']),
            'subtasks.*.necessary_skills.*.name.max' => json_encode(['el' => 'subtasksNecessarySkills', 'message' => 'Название навыка не должно превышать 50 символов']),
            'subtasks.*.number_executors.required' => json_encode(['el' => 'subtasksNumberExecutors', 'message' => 'Введите количество исполнителей']),
            'subtasks.*.number_executors.min' => json_encode(['el' => 'subtasksNumberExecutors', 'message' => 'Минимальное количество исполнителей – 1 человек!']),
            'subtasks.*.number_executors.numeric' => json_encode(['el' => 'subtasksNumberExecutors', 'message' => 'Количество исполнителей должно быть в виде числа!']),
            'subtasks.*.number_executors.max' => json_encode(['el' => 'subtasksNumberExecutors', 'message' => 'Максимальное количество исполнителей – 10 человек!']),
            'subtasks.*.task_name.required' => json_encode(['el' => 'subtasksTaskName', 'message' => 'Введите название задачи']),
            'subtasks.*.task_name.min' => json_encode(['el' => 'subtasksTaskName', 'message' => 'Название задачи должно быть больше 2 символов']),
            'subtasks.*.task_name.max' => json_encode(['el' => 'subtasksTaskName', 'message' => 'Название задачи не должно превышать 255 символа']),
            'tender_closing.required' => json_encode(['el' => 'tenderClosing', 'message' => 'Выберите продолжительность тендера']),
            'type_project.title.required' => json_encode(['el' => 'typeProject', 'message' => 'Выберите категорию проекта']),
            'type_project.title.min' => json_encode(['el' => 'typeProject', 'message' => 'Название категории проекта должно быть не менее 1 символа']),
            'type_project.title.max' => json_encode(['el' => 'typeProject', 'message' => 'Название категории проекта должно быть не более 255 символов']),
        ];
    }
}
