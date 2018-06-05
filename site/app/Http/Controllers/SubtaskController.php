<?php

namespace App\Http\Controllers;

use App\Category;
use App\Subtask;
use App\Http\Requests\FindSubtaskRequest;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    /**
     * Страница со всеми подзадачами
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
     * Добавляет пользователя, желающего участвовать в тендере
     */
    public function store(Request $request)
    {
        return Subtask::addTaskExecutors(
            $request->input('taskId'), 
            $request->input('comment')
        );
    }

    /**
     * возвращает страницу со всеми подзадачами по её категории
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

    /**
     * возвращает страницу со всеми подзадачами по категории проекта
     */
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

    /*
    * Возвращает список задач
    */
    public function listOfTaskName(Request $request) 
    {
        return Subtask::select('id', 'task_name')
            ->where(
                'task_name',
                'like',
                '%'.$request->input('taskName').'%'
            )->get();
    }

    /*
    * возвращает страницу с найденными задачами
    */
    public function findTasks(FindSubtaskRequest $request) 
    {
        $query = $request->input('q');
        $SubtaskList = Subtask::findTasks($query);

        $CategoryList = Category::with('children.children', 'children.parent')
            ->where('parent_id', 0)->get();

        return view('subtasks', [
            'q' => $query,
            'projectTypeList' => $CategoryList,
            'delimiter' => '',
            'SubtaskList' => $SubtaskList
        ]);
    }
}
