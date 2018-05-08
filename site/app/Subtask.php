<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    protected $fillable = [
        'category_id', 'number_executors', 'description'
    ];

    // подзадача относится к проекту
    public function project() {
        return $this->belongsTo('App\Project');
    }

    // подзадача относится к категории
    public function category() {
        return $this->belongsTo('App\Category');
    }

    // подзадачу выполняют несколько исполнителей
    public function taskExecutors() {
        return $this->hasMany('App\TaskExecutor');
    }
}
