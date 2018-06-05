<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    //Задачи соответствуют данному навыку
    public function tasks() {
        return $this->belongsToMany('App\Subtask', 'necessary_skills');
    }

    // возвращает список id скилов
    public static function getSkillIdList(Array $skillList):Array {
        $skillIdList = [];
        foreach($skillList as $skill) {
            if($skill['id'] == -1) { // новый скил необходимо добавить в БД
                $newSkill = self::firstOrCreate(['name' => $skill['name']]);
                $skillIdList[] = $newSkill->id;
            } else {
                $skillIdList[] = $skill['id'];
            }
        }
        return $skillIdList;
    }
}
