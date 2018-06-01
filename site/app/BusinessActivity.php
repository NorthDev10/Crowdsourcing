<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessActivity extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
    ];
    
    // сфера деятельности относится к проекту
    public function project() {
        return $this->belongsTo('App\Project');
    }

    public static function addBusinessActivity(string $businessActivityName):int {
        $businessActivity = self::firstOrCreate([
            'title' => $businessActivityName,
        ]);
        return $businessActivity->id;
    }
}
