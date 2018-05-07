<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // комментарий принадлежит проекту
    public function project() {
        return $this->belongsTo('App\Project');
    }

    // комментарий принадлежит пользователю
    public function user() {
        return $this->belongsTo('App\User');
    }
}
