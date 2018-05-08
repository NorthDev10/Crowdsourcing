<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $fillable = [
        'category_id', 'project_name', 'project_description',
        'deadline', 'tender_closing'
    ];

    // проектом управляет следующий заказчик
    public function customer() {
        return $this->belongsTo('App\User', 'user_id');
    }

    // Перечень необходимых навыков от исполнителя
    public function necessarySkills() {
        return $this->belongsToMany('App\Skill', 'necessary_skills');
    }

    // Проект относится к категории
    public function category() {
        return $this->belongsTo('App\Category');
    }

    // проект разделён на подзадачи
    public function subtasks() {
        return $this->hasMany('App\Subtask');
    }

    // у проекта есть отзывы
    public function reviews() {
        return $this->hasMany('App\Review');
    }

    // пользователи, желающие поучаствовать в данном проекте
    public function executors() {
        return $this->hasManyThrough('App\TaskExecutor', 'App\Subtask');
    }
}
