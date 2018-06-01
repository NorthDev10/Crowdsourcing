<?php

namespace App;

use Auth;
use Carbon\Carbon;
use App\Skill;
use App\Subtask;
use App\Category;
use App\BusinessActivity;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'type_project_id', 'business_activity_id', 'brand',
        'project_name', 'project_description',
        'deadline', 'tender_closing', 'slug', 'status'
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

    // Проект относится к сфере деятельности
    public function businessActivity() {
        return $this->belongsTo('App\BusinessActivity');
    }

    // до истечения срока проекта
    public function beforeDeadlineLeft() {
        $deadline = Carbon::parse($this->deadline);
        return $deadline->diffInDays($this->created_at);
    }

    // до истечения тендера проекта
    public function beforeTenderLeft() {
        return Carbon::parse($this->created_at)->addDays($this->tender_closing);
    }

    //тендер должен закончится раньше чем крайний срок
    public static function checkDeadline(
        $createdProject,
        int $tenderClosing, 
        string $deadline):bool {
            if($createdProject == null) {
                $createdProject = Carbon::now();
            }
            $durationTender = Carbon::parse($createdProject)
                    ->addDays($tenderClosing+1);
            return Carbon::parse($deadline)->gt($durationTender);
    }
    
    // название категории, которой принадлежит проект
    public function typeProjectName():string {
        if(is_null($this->typeProject)) {
            return $this->slug;
        } else {
            return $this->typeProject->slug;
        }
    }

    public function status():bool {
        if($this->status == 'opened' && ($this->beforeDeadlineLeft() > 0)) {
            return true; // в проект ищут исполнителей
        }
        return false;
    }

    public static function createProject(Request $request) {
        $projectData = $request->all();

        $projectData['type_project_id'] = self::getCategoryProjectId(
            $projectData['type_project']
        );

        $projectData['business_activity_id'] = self::getBrandProjectId(
            $projectData['business_activity']
        );

        $slug = Carbon::now()->format('d-m-Y-').mt_rand(100,999).'-'.$projectData['project_name'];
        $projectData['slug'] = str_slug($slug, '-');

        $project = Auth::user()->projects()->create($projectData);

        self::fillSubtask($project, $projectData);

        if($project) {
            return ['status' => true];
        } else {
            return ['status' => false];
        }
    }

    public static function updateProject(Request $request, Project $project) {
        $projectData = $request->all();
        
        $projectData['type_project_id'] = self::getCategoryProjectId(
            $projectData['type_project']
        );

        $projectData['business_activity_id'] = self::getBrandProjectId(
            $projectData['business_activity']
        );

        $project->fill($projectData);

        self::fillSubtask($project, $projectData);

        if($project->update()) {
            return ['status' => true];
        } else {
            return ['status' => false];
        }
    }

    protected static function getCategoryProjectId(array $project):int {
        // сохраняем id категории, к которой относится проект
        $typeProjectId = $project['id'];
        if($typeProjectId == -1) {
            $typeProjectId = Category::addCategory(
                0, 
                $project['title']
            );
        }
        return $typeProjectId;
    }

    protected static function getBrandProjectId(array $project) {
        // сохраняем id сферы деятельности компании
        $businessActivityId = $project['id'];
        if($businessActivityId == -1) {
            $businessActivityId = BusinessActivity::addBusinessActivity(
                $project['title']
            );
        }
        return $businessActivityId;
    }

    protected static function fillSubtask(Project $project, Array $projectData) {
        $removeTaskIdList = [];
        $subtaskList = [];
        foreach($project->subtasks as $subtask) {
            $subtaskId = $subtask->searchTaskInList($subtask->id, $projectData['subtasks']);
            if($subtaskId >= 0) {// обновляем подзадачи
                Subtask::updateSubtask(
                    $subtask, 
                    $projectData['subtasks'][$subtaskId], 
                    $projectData['type_project_id']
                );
                $subtaskList[] = $subtask;
            } else {// добавляем задачи в список на удаления
                $removeTaskIdList[] = $subtask->id;
            }
        }

        Subtask::destroy($removeTaskIdList);

        // добавляем новые подзадачи в список
        foreach($projectData['subtasks'] as $key => $newSubtask) {
            if($newSubtask['id'] == -1) {
                $subtask = new Subtask();
                Subtask::updateSubtask(
                    $subtask, 
                    $newSubtask, 
                    $projectData['type_project_id']
                );
                // сохраняем ссылку на объект,
                // для дальнейшего использования в necessary_skills
                $projectData['subtasks'][$key]['subtaskObj'] = $subtask;
                $subtaskList[] = $subtask;
            }
        }

        $project->subtasks()->saveMany($subtaskList);
        // сохраняем новые подзадачи
        if(count($subtaskList) > 0) {
            // после того, как у нас появились id subtasks, выполним добавлении скилов
            foreach($projectData['subtasks'] as $newSubtask) {
                if($newSubtask['id'] == -1) {
                    // все скилы, которые не были в списке - открепляются
                    $newSubtask['subtaskObj']->necessarySkills()->sync(
                        Skill::getSkillIdList(
                            $newSubtask['necessary_skills']
                        )
                    );
                }
            }
        }
    }
}
