<?php

namespace App;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class TaskExecutor extends Model
{
    protected $fillable = [
        'comment', 'user_id'
    ];

    // данные исполнителя
    public function user() {
        return $this->belongsTo('App\User');
    }

    // Пользователь выполняет следующую задачу
    public function subtask() {
        return $this->belongsTo('App\Subtask');
    }

    public static function pickUpTask(Request $request) {
        $executor = TaskExecutor::with('subtask.project', 'user')
            ->where('subtask_id', $request->input('subtask_id'))
            ->where('user_id', $request->input('user_id'))
            ->first();

        if($executor->subtask->project->user_id == Auth::user()->id) {
            if($executor->user_selected == 1) {
                $executor->user_selected = 0;
                $executor->subtask->involved_executors--;
                $executor->subtask->save();
                $executor->save();
                return redirect()->back()->with('status', 
                    'Пользователь '.$executor->user->name.
                    ' отклонён от задачи: '.
                    $executor->subtask->task_name);
            } else {
                return redirect()->back()->with('status', 
                    'пользователь '.$executor->user->name.' не участвовал в задании');
            }
        }
    }

    public static function giveTheTask(Request $request) {
        $executor = TaskExecutor::with('subtask.project', 'user')
            ->where('subtask_id', $request->input('subtask_id'))
            ->where('user_id', $request->input('user_id'))
            ->first();

        if($executor->subtask->project->user_id == Auth::user()->id) {
            if($executor->subtask->number_executors > $executor->subtask->involved_executors) {
                if($executor->user_selected == 0) {
                    $executor->user_selected = 1;
                    $executor->subtask->involved_executors++;
                    $executor->subtask->save();
                    $executor->save();
                    return redirect()->back()->with('status', 
                        'Пользователь '.$executor->user->name.
                        ' приступил к выполнению задачи: '.
                        $executor->subtask->task_name);
                } else {
                    return redirect()->back()->with('status', 
                        'пользователь '.$executor->user->name.' уже выбран');
                }
            } else {
                return redirect()->back()->with('status', 'Нет свободных мест!');
            }
        }
    }
}
