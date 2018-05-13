<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'category_id', 'project_name', 'project_description',
        'deadline', 'tender_closing', 'status'
    ];

    // проектом управляет следующий заказчик
    public function customer() {
        return $this->belongsTo('App\User', 'user_id');
    }

    // Проект относится к типу
    public function typeProject() {
        return $this->belongsTo('App\Category', 'type_project_id');
    }

    // проект разделён на подзадачи
    public function subtasks() {
        return $this->hasMany('App\Subtask');
    }

    // у проекта есть отзывы
    public function reviews() {
        return $this->hasMany('App\Review');
    }

    // пользователи, желающие поучаствовать в данном проекте
    public function executors() {
        return $this->hasManyThrough('App\TaskExecutor', 'App\Subtask');
    }

    // до истечения срока проекта
    public function beforeDeadlineLeft() {
        $deadline = Carbon::parse($this->deadline);
        return $deadline->diffInDays($this->created_at);
    }
    
    // название категории, которой принадлежит проект
    public function typeProjectName() {
        if(is_null($this->typeProject)) {
            return $this->slug;
        } else {
            return $this->typeProject->slug;
        }
    }

    public function status() {
        if($this->status == 'opened' && ($this->beforeDeadlineLeft() > 0)) {
            return true; // в проект ищут исполнителей
        }
        return false;
    }
}
