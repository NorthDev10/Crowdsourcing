<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupChat extends Model
{
    // сообщение принадлежит пользователю
    public function user() {
        return $this->belongsTo('App\User');
    }

    // сообщение принадлежит проекту
    public function project() {
        return $this->belongsTo('App\Project');
    }
}
