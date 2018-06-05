<?php

namespace App;

use Auth;
use App\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Пользователь обладает следующими навыками
    public function skills() {
        return $this->belongsToMany('App\Skill', 'user_skills');
    }

    // У заказчика есть много проектов
    public function projects() {
        return $this->hasMany('App\Project');
    }

    // Пользователь оставил следующие отзывы
    public function myReviews() {
        return $this->hasMany('App\Review', 'reviewer_id');
    }

    // Отзывы от пользователей
    public function reviewsFromUsers() {
        return $this->hasMany('App\Review');
    }
    
    // комментарии к задачам
    public function commentsOnTasks() {
        return $this->hasMany('App\TaskExecutor');
    }

    // пользователь выполняет следующие задачи
    public function subtasks() {
        return $this->belongsToMany('App\Subtask', 'task_executors')
            ->where('user_selected', true);
    }

    public static function updateProfile(Request $request, User $user):array {
        if(Auth::user()->id == $user->id) {
            $userData = $request->except('password');

            if($request->has('password')) {
                $userData['password'] = Hash::make($request->input('password'));
                $userData['remember_token'] = str_random(10);
            }
            
            $user->fill($userData);
            // все скилы, которые не были в списке - открепляются
            $user->skills()->sync(
                Skill::getSkillIdList(
                    $userData['skills']
                )
            );
            if($user->update()) {
                return ['status' => true];
            } else {
                return ['status' => false];
            }
        } else {
            return ['status' => false, 'message' => 'Вы можете редактировать только свой профиль!'];
        }
    }

    public static function getMyProfile() {
        $user = Auth::user();
        $user->load([
            'skills',
            'reviewsFromUsers.project.typeProject',
            'reviewsFromUsers.reviewer',
            'commentsOnTasks.subtask.project.typeProject'
        ]);

        //answer использует 2 параметра от родительского объекта,
        // поэтому используем “ленивую” загрузку вложенных моделей
        $user->reviewsFromUsers->load('answer');

        return $user;
    }
}
