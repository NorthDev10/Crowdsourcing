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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $project->load('subtasks.category',
        'businessActivity', 'typeProject',
        'subtasks.necessarySkills');

        return $project;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
