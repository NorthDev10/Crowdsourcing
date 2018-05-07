<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // отзыв принадлежит проекту
    public function project() {
        return $this->belongsTo('App\Project');
    }

    // отзыв принадлежит пользователю
    public function user() {
        return $this->belongsTo('App\User');
    }

    // отзыв написал пользователь
    public function reviewer() {
        return $this->belongsTo('App\User', 'reviewer_id');
    }
}
