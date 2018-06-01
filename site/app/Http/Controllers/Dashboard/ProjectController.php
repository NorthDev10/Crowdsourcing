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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Dashboard.myProjectsCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('Dashboard.myProjectsEdit', [
            'project' => $project
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($projectSlug, Request $request)
    {
        return view('Dashboard.myProjectsEdit', [
            'isEditingPage' => 'edit',
            'projectSlug' => $projectSlug
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
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
