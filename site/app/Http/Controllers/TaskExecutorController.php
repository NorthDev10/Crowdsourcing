<?php

namespace App\Http\Controllers;

use Auth;
use App\TaskExecutor;
use App\Http\Requests\TaskControlRequest;
use Illuminate\Http\Request;

class TaskExecutorController extends Controller
{
    /**
     * Обновляет статус участие пользователя в задачи проекта
     */
    public function update(TaskControlRequest $request)
    {
        $executor = TaskExecutor::getTaskExecutor(
            $request->input('subtask_id'), 
            $request->input('user_id')
        );

        if($executor == null) {
            return redirect()->back()->with('status', 'Не удалось найти подзадачу');
        }

        if($request->input('user_selected') == 1) {
            return redirect()->back()->with(
                'status', 
                TaskExecutor::giveTheTask($executor)['status']
            );
        } else {
            return redirect()->back()->with(
                'status', 
                TaskExecutor::pickUpTask($executor)['status']
            );
        }
    }
}
