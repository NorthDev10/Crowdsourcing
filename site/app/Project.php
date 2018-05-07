<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // проектом управляет следующий заказчик
    public function customer() {
        return $this->belongsTo('App\User');
    }

    // Перечень необходимых навыков от исполнителя
    public function necessarySkills() {
        return $this->belongsToMany('App\Skill', 'necessary_skills');
    }

    // комментарии к проекту
    public function comments() {
        return $this->hasMany('App\Comment');
    }

    // Проект относится к категории
    public function category() {
        return $this->belongsTo('App\Category');
    }

    // желающие поучаствовать в проекте
    public function projectExecutors() {
        return $this->hasMany('App\ProjectExecutor');
    }

    // у проекта есть отзывы
    public function reviews() {
        return $this->hasMany('App\Review');
    }
}
