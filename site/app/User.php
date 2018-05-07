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
        'name', 'email', 'password',
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

    // пользователь выполняет следующие проекты
    public function projectsInExecution() {
        return $this->hasMany('App\ProjectExecutor');
    }

    // Пользователь оставил следующие комментарии
    public function comments() {
        return $this->hasMany('App\Comment');
    }

    // пользователь оставил следующие сообщения в чате
    public function groupChats() {
        return $this->hasMany('App\GroupChat');
    }
}
