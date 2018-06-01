<?php

namespace App\Http\Controllers;

use Auth;
use App\TaskExecutor;
use Illuminate\Http\Request;

class TaskExecutorController extends Controller
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
        //
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
     * @param  \App\TaskExecutor  $taskExecutor
     * @return \Illuminate\Http\Response
     */
    public function show(TaskExecutor $taskExecutor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaskExecutor  $taskExecutor
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskExecutor $taskExecutor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskExecutor  $taskExecutor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskExecutor $taskExecutor)
    {
        if($request->input('user_selected') == 1) {
            return TaskExecutor::giveTheTask($request);
        } else {
            return TaskExecutor::pickUpTask($request);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskExecutor  $taskExecutor
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskExecutor $taskExecutor)
    {
        //
    }
}
