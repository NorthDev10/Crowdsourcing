<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    public $timestamps = false;

    //Проекты соответствуют данному навыку
    public function projects() {
        return $this->belongsToMany('App\Project', 'necessary_skills');
    }
}
