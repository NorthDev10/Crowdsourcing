<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    public $timestamps = false;

    // все проекты по категории
    public function projects() {
        return $this->hasMany('App\Project');
    }

    // все задачи по категории
    public function subtasks() {
        return $this->hasMany('App\Subtask');
    }

    // возвращает подкатегории
    public function children() {
        return $this->hasMany(self::class, 'parent_id');
    }
}
