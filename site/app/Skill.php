<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    public $timestamps = false;

    //Задачи соответствуют данному навыку
    public function tasks() {
        return $this->belongsToMany('App\Subtask', 'necessary_skills');
    }
}
