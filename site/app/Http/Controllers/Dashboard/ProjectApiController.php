<?php

namespace App\Http\Controllers\Dashboard;

use App\Project;
use Auth;
use App\BusinessActivity;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Controllers\Controller;

class ProjectApiController extends Controller
{

    /**
     * Создаёт новый проект
     */
    public function store(ProjectRequest $request)
    {
        if(Project::checkDeadline(
            null,
            $request->input('tender_closing'), 
            $request->input('deadline'))
        ) {
            return Project::createProject($request);
        } else {
            return ['status' => false, 'message' => 'Введите для проекта адекватный конечный срок!'];
        }
    }

    /**
     * Возвращает данные для редактирования проекта
     */
    public function edit(Project $project)
    {
        $project->load('subtasks.category',
        'businessActivity', 'typeProject',
        'subtasks.necessarySkills');

        return $project;
    }

    /**
     * Обновляет проект
     */
    public function update(ProjectRequest $request, Project $project)
    {
        if(Project::checkDeadline(
            $project->created_at, 
            $request->input('tender_closing'), 
            $request->input('deadline'))
        ) {
            if(Auth::user()->id == $project->user_id) {
                return Project::updateProject($request, $project);
            } else {
                return ['status' => false, 'message' => 'Вы можете редактировать только свой проект!'];
            }
        } else {
            return ['status' => false, 'message' => 'Введите для проекта адекватный конечный срок!'];
        }
    }
}
