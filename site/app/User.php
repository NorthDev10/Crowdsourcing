<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Пользователь обладает следующими навыками
    public function skills() {
        return $this->belongsToMany('App\Skill', 'user_skills');
    }

    // У заказчика есть много проектов
    public function projects() {
        return $this->hasMany('App\Project');
    }

    // Пользователь оставил следующие отзывы
    public function myReviews() {
        return $this->hasMany('App\Review', 'reviewer_id');
    }

    // Отзывы от пользователей
    public function reviewsFromUsers() {
        return $this->hasMany('App\Review');
    }

    // комментарии к задачам
    public function commentsOnTasks() {
        return $this->hasMany('App\TaskExecutor');
    }

    // пользователь выполняет следующие задачи
    public function subtasks() {
        return $this->belongsToMany('App\Subtask', 'task_executors');
    }
}
