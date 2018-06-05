<?php

namespace App\Http\Controllers;

use Config;
use App\Category;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Возвращает страницу проекта
     */
    public function show($typeProject, $typeCategoryId, Project $project)
    {
        return view('project', [
            'project' => $project,
            'status' => $project->status(),
            'categorySubtasks' => Category::tasksByProject($project->id),
        ]);
    }
}
