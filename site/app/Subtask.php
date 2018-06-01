<?php

namespace App;

use Auth;
use Config;
use App\Skill;
use Carbon\Carbon;
use App\TaskExecutor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subtask extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'task_name', 'number_executors', 
        'involved_executors', 'description'
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
            ->whereRaw('DATE_ADD(projects.created_at, INTERVAL projects.tender_closing DAY) >= \'' . Carbon::today()->toDateString(). '\'')
            ->paginate(
                Config::get('settings.project.projects_per_page')
            );
    }

    public static function getAllSubtasksByType(int $categoryId) {
        return Subtask::with('category', 
            'project.typeProject', 'necessarySkills')
            ->select('subtasks.*')
            ->join('projects', 'subtasks.project_id', '=', 'projects.id')
            ->where('type_project_id', $categoryId)
            ->whereColumn('number_executors', '>', 'involved_executors')
            ->where('subtasks.status', 0)
            ->where('projects.status', 'opened')
            ->whereDate('projects.deadline', '>', Carbon::today()->toDateString())
            ->whereRaw('DATE_ADD(projects.created_at, INTERVAL projects.tender_closing DAY) >= \'' . Carbon::today()->toDateString(). '\'')
            ->paginate(
                Config::get('settings.project.projects_per_page')
            );
    }

    public static function getAllSubtasksByCategory(int $typeOfProjectId, int $categoryId) {
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
            ->whereRaw('DATE_ADD(projects.created_at, INTERVAL projects.tender_closing DAY) >= \'' . Carbon::today()->toDateString(). '\'')
            ->paginate(
                Config::get('settings.project.projects_per_page')
            );
    }

    // добавляем исполнителя к задаче
    public static function addTaskExecutors(int $taskId, $comment) {
        $subtask = Subtask::with('project')->find($taskId);
        if(!$subtask->status) {
            if($subtask->number_executors > $subtask->involved_executors) {
                if(Auth::user()->id != $subtask->project->user_id) {
                    if($subtask->taskExecutors
                        ->where('user_id', Auth::user()->id)->first() == null) {
                        if(self::skillCheck($subtask->necessarySkills, Auth::user()->skills)) {
                            $taskExecutor = new TaskExecutor([
                                'user_id' => Auth::user()->id,
                                'comment' => $comment,
                            ]);
                            $subtask->taskExecutors()->save($taskExecutor);
                            return redirect()->back()->with('status', 'Теперь вы участвуете в тендере!');
                        } else {
                            return redirect()->back()->with('status', 'Для выполнения этой задачи, у вас недостаточно навыков!');
                        }
                    } else {
                        return redirect()->back()->with('status', 'Вы уже участвуете в данной задачи!');
                    }
                } else {
                    return redirect()->back()->with('status', 'Вы не можете участвововать в своём проекте!');
                }
            } else {
                return redirect()->back()->with('status', 'Нет свободных мест!');
            }
        }
    }

    //проверяем необходимые навыки
    public static function skillCheck(
        Object $necessarySkills, Object $availableSkills
    ) {
        foreach($necessarySkills as $skill) {
            // выполняем поиск в коллекции
            if($availableSkills->where('id', $skill->id)->first() == null) {
                return false; // нет необходимого навыка
            }
        }
        return true;
    }

    public function searchTaskInList(int $id, Array $subtaskList): int {
        foreach($subtaskList as $key => $subtask) {
            if($subtask['id'] == $id) {
                return $key;
            }
        }
        return -1;
    }

    public static function updateSubtask(Subtask &$oldSubtask, Array $newSubtask, int $typeProjectId) {
        if($oldSubtask->id != null) {
            // все скилы, которые не были в списке - открепляются
            $oldSubtask->necessarySkills()->sync(
                Skill::getSkillIdList(
                    $newSubtask['necessary_skills']
                )
            );
        }

        $categoryId = $newSubtask['category']['id'];
        if($categoryId == -1) {// пользователь ввёл новую категорию
            $categoryId = Category::addCategory(
                $typeProjectId, 
                $newSubtask['category']['title']
            );
        }
        $newSubtask['category_id'] = $categoryId;

        $taskName = $newSubtask['task_name'];
        if(gettype($taskName) == 'array') {
            $taskName = $taskName['task_name'];
        }
        $newSubtask['task_name'] = $taskName;
        $oldSubtask->fill($newSubtask);
    }
}
