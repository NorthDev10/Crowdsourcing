<?php

namespace App;

use Auth;
use Config;
use Lang;
use App\Project;
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
        return self::getSubtasks();
    }

    public static function getAllSubtasksByType(int $typeOfProjectId) {
        return self::getSubtasks($typeOfProjectId);
    }

    public static function getAllSubtasksByCategory(int $typeOfProjectId, int $categoryId) {
        return self::getSubtasks($typeOfProjectId, $categoryId);
    }

    public static function getSubtasks(int $typeOfProjectId = null, int $categoryId = null) {
        $subtask = Subtask::query();
        $subtask->with('category', 'project.typeProject', 'necessarySkills');

        if(!empty($categoryId)) {// категория подзадачи
            $subtask->where('category_id', $categoryId);
        }

        $subtask->where('status', 0)
            ->whereColumn('number_executors', '>', 'involved_executors')
            ->whereHas('project', function ($query) use ($typeOfProjectId) {
                $query->where('status', 'opened');

                if(!empty($typeOfProjectId)) {// категория проекта
                    $query->where('type_project_id', $typeOfProjectId);
                }

                $query->whereDate('deadline', '>', Carbon::today()->toDateString());
                // Вывести все проекты, у которых провидится тендер
                //(Дата создания проекта + кол. дней) > = текущая дата
                $query->whereRaw('DATE_ADD(created_at, INTERVAL tender_closing+1 DAY) >= \'' . Carbon::today()->toDateString(). '\'');
            });

        return $subtask->paginate(
            Config::get('settings.project.projects_per_page')
        );
    }

    // добавляем исполнителя к задаче
    public static function addTaskExecutors(int $taskId, $comment) {
        $subtask = Subtask::with('project')->find($taskId);
        if($subtask != null && !$subtask->status) {
            if($subtask->number_executors > $subtask->involved_executors) {
                if(Auth::user()->id != $subtask->project->user_id) {
                    if(!self::isUserWillingTask($subtask, Auth::user()->id)) {
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
        } else {
            return redirect()->back()->with('status', 'Не удалось найти подзадачу');
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

    public static function findTasks($query) {
        $keyWordList = explode(' ', trim($query));

        if(count($keyWordList) > 0) {
            $Subtask = Subtask::query();
            $Subtask->with('necessarySkills', 'project.typeProject');
            $Subtask->where('status', false); // Задача ещё не выполнена
            // Вывод родительской модели зависит от вложенной,
            // поиск во вложенной модели necessarySkills
            $Subtask->whereHas('necessarySkills', function ($query) use ($keyWordList) {
                $query->where('name', 'like', '%'.$keyWordList[0].'%');

                for($i = 1; $i < count($keyWordList); ++$i) {
                    if(!empty(trim($keyWordList[$i]))) {
                        $query->orWhere('name', 'like', '%'.$keyWordList[$i].'%');
                    }
                }
            });
            // Поиск во вложенной модели project
            $Subtask->whereHas('project', function ($query) {
                $query->where('status', 'opened');
            });

            return $Subtask->paginate(
                Config::get('settings.project.projects_per_page')
            );
        }
    }

    // Проверяем, участвует ли пользователь в задаче
    public static function isUserInvolveTask(Subtask $subtask, int $userId):bool
    {
        $taskExecutor = $subtask->taskExecutors->where('user_id', $userId)->first();
        if($taskExecutor != null) {
            if($taskExecutor->user_selected) {
                return true;
            }
        }
        return false;
    }

    // Проверяем, если ли пользователь среди желающих
    public static function isUserWillingTask(Subtask $subtask, int $userId):bool
    {
        if($subtask->taskExecutors->where('user_id', $userId)->first() != null) {
            return true;
        }
        return false;
    }

    public function durationTask($beganPerformTask, Project $project):string {
        if($project->status == 'closed' && $this->status == 0) {
            $executionTasks = Carbon::parse($project->updated_at);
        } else {
            if($this->status) {
                $executionTasks = Carbon::parse($this->updated_at);
            } else {
                $executionTasks = Carbon::now();
            }
        }
        
        $days = $executionTasks->diffInDays(Carbon::parse($beganPerformTask));
        if($days > 0) {
            return $days.' '.Lang::choice('день|дня|дней', $days, [], 'ru');
        } else {
            $hours = $executionTasks->diffInHours(Carbon::parse($beganPerformTask));
            if($hours > 0) {
                return $hours.' '.Lang::choice('час|часа|часов', $hours, [], 'ru');
            } else {
                $minutes = $executionTasks->diffInMinutes(Carbon::parse($beganPerformTask));
                return $minutes.' '.Lang::choice('минута|минуты|минут', $minutes, [], 'ru');
            }
        }
    }

    public function status(Project $project) {
        if($project->status == 'closed' && $this->status == 0) {
            return 'не выполнена';
        } else {
            if($this->status) {
                return 'выполнена';
            } else {
                return 'выполняется';
            }
        }
    }
}
