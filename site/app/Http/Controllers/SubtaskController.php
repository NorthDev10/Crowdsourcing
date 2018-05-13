<?php

namespace App\Http\Controllers;

use App\Category;
use App\Subtask;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $SubtaskList = Subtask::getAllSubtasks();

        $CategoryList = Category::with('children.children', 'children.parent')
            ->where('parent_id', 0)->get();

        return view('subtasks', [
            'projectTypeList' => $CategoryList,
            'delimiter' => '',
            'SubtaskList' => $SubtaskList
        ]);
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
     * @param  \App\Subtask  $subtask
     * @return \Illuminate\Http\Response
     */
    public function show($typeOfProject, $category, $categoryId)
    {
        $CategoryList = Category::with('children.children', 'children.parent')->get();
        $category = $CategoryList->where('id', $categoryId)->first();
        $typeOfProject = $CategoryList->where('id', $category->parent_id)->first();

        $SubtaskList = Subtask::getAllSubtasksByCategory($typeOfProject->id, $categoryId);

        return view('subtasks', [
            'projectTypeList' => $CategoryList->where('parent_id', 0),
            'delimiter' => '',
            'typeOfProject' => $typeOfProject,
            'category' => $category,
            'SubtaskList' => $SubtaskList
        ]);
    }

    public function showProjectsByType($typeOfProject, $id_category)
    {
        $SubtaskList = Subtask::getAllSubtasksByType($id_category);

        $CategoryList = Category::with('children.children', 'children.parent')
            ->where('parent_id', 0)->get();

        return view('subtasks', [
            'projectTypeList' => $CategoryList,
            'delimiter' => '',
            'typeOfProject' => $CategoryList->where('id', $id_category)->first(),
            'SubtaskList' => $SubtaskList
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subtask  $subtask
     * @return \Illuminate\Http\Response
     */
    public function edit(Subtask $subtask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subtask  $subtask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subtask $subtask)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subtask  $subtask
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subtask $subtask)
    {
        //
    }
}
