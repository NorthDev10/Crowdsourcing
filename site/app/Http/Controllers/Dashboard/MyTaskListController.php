<?php

namespace App\Http\Controllers\Dashboard;

use Auth;
use Config;
use App\Subtask;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MyTaskListController extends Controller
{
    /**
     * выдаёт список задач пользователя
     */
    public function index()
    {
        $mySubtasks = Auth::user()
                    ->subtasks()
                    ->with('project.typeProject')
                    ->orderBy('created_at', 'desc')
                    ->paginate(Config::get('settings.myTasks.tasks_per_page'));

        return view('Dashboard.myTasks', [
            'mySubtasks' => $mySubtasks
        ]);
    }

    /**
     * Отмечает задачу как выполнена
     */
    public function update(Request $request, Subtask $subtask)
    {
        if(Subtask::isUserInvolveTask($subtask, Auth::user()->id)) {
            if($subtask->project->status != 'closed') {
                $subtask->status = 1;
                if($subtask->save()) {
                    return redirect()->back();
                } else {
                    return redirect()->back()->with('status', 'Что-то пошло не так =(');
                }
            } else {
                return redirect()->back()->with('status', 'ВЫ опоздали! Проект уже закрыт.');
            }
        } else {
            abort(404);
        }
    }
}
