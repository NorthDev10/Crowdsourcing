<?php

namespace App;

use Config;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    protected $fillable = [
        'category_id', 'number_executors', 'description'
    ];

    // подзадача относится к проекту
    public function project() {
        return $this->belongsTo('App\Project');
    }

    // подзадачу выполняют несколько исполнителей
    public function taskExecutors() {
        return $this->hasMany('App\TaskExecutor');
    }

    // Проект относится к категории
    public function category() {
        return $this->belongsTo('App\Category');
    }

    // Перечень необходимых навыков от исполнителя
    public function necessarySkills() {
        return $this->belongsToMany('App\Skill', 'necessary_skills');
    }

    public static function getAllSubtasks() {
        return Subtask::with('category', 
            'project.typeProject', 'necessarySkills')
            ->select('subtasks.*')
            ->join('projects', 'subtasks.project_id', '=', 'projects.id')
            ->whereColumn('number_executors', '>', 'involved_executors')
            ->where('subtasks.status', 0)
            ->where('projects.status', 'opened')
            ->whereDate('projects.deadline', '>', Carbon::today()->toDateString())
            ->whereDate('projects.tender_closing', '>', Carbon::today()->toDateString())
            ->paginate(
                Config::get('settings.project.projects_per_page')
            );
    }

    public static function getAllSubtasksByType($categoryId) {
        return Subtask::with('category', 
            'project.typeProject', 'necessarySkills')
            ->select('subtasks.*')
            ->join('projects', 'subtasks.project_id', '=', 'projects.id')
            ->where('type_project_id', $categoryId)
            ->whereColumn('number_executors', '>', 'involved_executors')
            ->where('subtasks.status', 0)
            ->where('projects.status', 'opened')
            ->whereDate('projects.deadline', '>', Carbon::today()->toDateString())
            ->whereDate('projects.tender_closing', '>', Carbon::today()->toDateString())
            ->paginate(
                Config::get('settings.project.projects_per_page')
            );
    }

    public static function getAllSubtasksByCategory($typeOfProjectId, $categoryId) {
        return Subtask::with('category', 
            'project.typeProject', 'necessarySkills')
            ->select('subtasks.*')
            ->join('projects', 'subtasks.project_id', '=', 'projects.id')
            ->where('type_project_id', $typeOfProjectId)
            ->where('category_id', $categoryId)
            ->whereColumn('number_executors', '>', 'involved_executors')
            ->where('subtasks.status', 0)
            ->where('projects.status', 'opened')
            ->whereDate('projects.deadline', '>', Carbon::today()->toDateString())
            ->whereDate('projects.tender_closing', '>', Carbon::today()->toDateString())
            ->paginate(
                Config::get('settings.project.projects_per_page')
            );
    }
}
