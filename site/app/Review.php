<?php

namespace App;

use Auth;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'project_id', 'description'
    ];

    // отзыв принадлежит проекту
    public function project() {
        return $this->belongsTo('App\Project');
    }

    // отзыв написан для исполнителя
    public function user() {
        return $this->belongsTo('App\User');
    }

    // отзыв написал пользователь
    public function reviewer() {
        return $this->belongsTo('App\User', 'reviewer_id');
    }

    // Пользователю написали ответный отзыв
    public function answer() {
        return $this->hasMany('App\Review', 'user_id', 'reviewer_id')
            ->where('project_id', $this->project_id)
            ->where('reviewer_id', $this->user_id);
    }

    public static function createReview(Request $request) {
        // Проверяем участников проекта
        $project = Project::with([
            'executors' => function($query) {
                $query->where('user_id', '=', Auth::user()->id)
                      ->where('user_selected', '=', true)->first();
            }
        ])->find($request->input('project_id'));

        // Отзыв к проекту, могут оставлять только участники проекта
        if(count($project->executors) > 0 || Auth::user()->id == $project->user_id) {
            $newReview = new Review();
            $newReview->reviewer_id = Auth::user()->id;
            // Отзыв пишется для следующего пользователя
            if(Auth::user()->id == $project->user_id) {
                //Заказчик оставляет отзыв
                $newReview->user_id = $request->input('user_id');
            } else {//Исполнитель оставляет отзыв заказчику
                $newReview->user_id = $project->user_id;
            }
            $newReview->fill($request->all());

            if($newReview->save()) {
                return redirect()->back()->with(
                    'status', 
                    'Ваш отзыв добавлен!'
                );
            }
        } else {
            return redirect()->back()->with(
                'status', 
                'Вы не можете добавить отзыв т.к. не участвуете в этом проекте!'
            );
        }
    }
}
