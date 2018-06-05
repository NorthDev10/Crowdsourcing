<?php

namespace App;

use Auth;
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

    public static function pickUpTask(TaskExecutor $executor) {
        if($executor->subtask->project->user_id == Auth::user()->id) {
            if($executor->subtask->project->status != 'closed') {
                if($executor->user_selected == 1) {
                    $executor->user_selected = 0;
                    $executor->subtask->involved_executors--;
                    $executor->push();
                    return redirect()->back()->with('status', 
                        'Пользователь '.$executor->user->name.
                        ' отклонён от задачи: '.
                        $executor->subtask->task_name);
                } else {
                    return redirect()->back()->with('status', 
                        'пользователь '.$executor->user->name.' не участвовал в задании');
                }
            } else {
                return redirect()->back()->with('status', 'Вы не можете забрать задачу у исполнителя!, поскольку проект завершён.');
            }
        }
    }

    public static function giveTheTask(TaskExecutor $executor) {
        if($executor->subtask->project->user_id == Auth::user()->id) {
            if($executor->subtask->project->status != 'closed') {
                if($executor->subtask->number_executors > $executor->subtask->involved_executors) {
                    if($executor->user_selected == 0) {
                        $executor->user_selected = 1;
                        $executor->subtask->involved_executors++;
                        $executor->push();
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
            } else {
                return redirect()->back()->with('status', 'Вы не можете выбрать исполнителя!, поскольку проект завершён.');
            }
        }
    }
    
    // возвращает исполнителя (пользователя) задачи и проект, в котором он участвует
    public static function getTaskExecutor(int $subtaskId, int $userId) {
        return TaskExecutor::with('subtask.project', 'user')
            ->where('subtask_id', $subtaskId)
            ->where('user_id', $userId)
            ->first();
    }
}
