<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskExecutor extends Model
{
    protected $fillable = [
        'comment'
    ];

    // данные исполнителя
    public function user() {
        return $this->belongsTo('App\User');
    }

    // Пользователь выполняет следующую задачу
    public function subtask() {
        return $this->belongsTo('App\Subtask');
    }
}
