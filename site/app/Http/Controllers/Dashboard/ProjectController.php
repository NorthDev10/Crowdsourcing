<?php

namespace App\Http\Controllers\Dashboard;

use Auth;
use Config;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * Возвращает список проектов
     */
    public function index()
    {
        $ProjectList = Auth::user()
                ->projects()
                ->paginate(Config::get('settings.project.projects_per_page'));

        return view('Dashboard.myProjects', [
            'ProjectList' => $ProjectList
        ]);
    }

    /**
     * возвращает страницу создания проекта
     */
    public function create()
    {
        return view('Dashboard.myProjectsCreate');
    }

    /**
     * возвращает страницу редактирования проекта
     */
    public function edit($projectSlug, Request $request)
    {
        return view('Dashboard.myProjectsEdit', [
            'isEditingPage' => 'edit',
            'projectSlug' => $projectSlug
        ]);
    }

    /**
     * Возвращает страницу процесса выполнения проекта
     */
    public function show(Project $project)
    {   
        $project->load(['executors' => function($query) {
            $query->where('user_selected', 1);
        }, 'executors.subtask', 'executors.user', 'customer']);

        return view('Dashboard.projectProcess', [
            'project' => $project,
        ]);
    }

    /**
     * обновление статуса проекта
     */
    public function update(Request $request, Project $project)
    {
        if(Auth::user()->id == $project->user_id) {
            $project->fill($request->all());
            $project->save();
            
            return redirect()->back()->with('status', 
                'Проект "'. $project->project_name . '" обновлён');
        } else {
            return redirect()->back()->with(
                'status', 
                'Вы можете редактировать только свой проект!'
            );
        }
    }

    /**
     * удаляет проект
     */
    public function destroy(Project $project)
    {
        if(Auth::user()->id == $project->user_id) {
            if($project->delete()) {
                return redirect()->back()->with(
                    'status', 
                    'Проект успешно удалён!'
                );
            } else {
                return redirect()->back()->with(
                    'status', 
                    'Что-то пошло не так =('
                );
            }
        } else {
            return redirect()->back()->with(
                'status', 
                'Вы можете удалять только свой проект!'
            );
        }
    }
}
