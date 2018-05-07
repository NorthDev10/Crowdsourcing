<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectExecutor extends Model
{
    // исполнители проекта общаются в чате
    public function groupChats() {
        return $this->hasMany('App\GroupChat', 'project_id', 'project_id');
    }

    // данные исполнителя
    public function user() {
        return $this->belongsTo('App\User');
    }

    // исполнитель работает над данным проектом
    public function project() {
        return $this->belongsTo('App\Project');
    }
}
