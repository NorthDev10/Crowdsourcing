<?php

namespace App\Http\Controllers;

use Auth;
use App\TaskExecutor;
use Illuminate\Http\Request;

class TaskExecutorController extends Controller
{
    /**
     * Обновляет статус участие пользователя в задачи проекта
     */
    public function update(Request $request, TaskExecutor $taskExecutor)
    {
        if($request->input('user_selected') == 1) {
            return TaskExecutor::giveTheTask($request);
        } else {
            return TaskExecutor::pickUpTask($request);
        }
    }
}
