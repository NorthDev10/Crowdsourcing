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
}
