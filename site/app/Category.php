<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'title', 'parent_id', 'slug'
    ];

    // все проекты по типу
    public function projectsByType() {
        return $this->hasMany('App\Project', 'type_project_id');
    }

    // все задачи по категории
    public function tasksByCategory() {
        return $this->hasMany('App\Subtask');
    }

    // возвращает подкатегории
    public function children() {
        return $this->hasMany(self::class, 'parent_id');
    }

    // возвращает родительскую категорию
    public function parent() {
        return $this->belongsTo(self::class);
    }

    // Возвращает подзадачи, относящиеся к данному проекту с id
    public static function tasksByProject($projectId) {
        return Category::with([
            'tasksByCategory' => function($query) use ($projectId) {
                $query->where('project_id', $projectId);
            },
            'tasksByCategory.necessarySkills'
        ])
            ->select('categories.*')
            ->join('subtasks', 'categories.id', '=', 'subtasks.category_id')
            ->where('subtasks.project_id', $projectId)
            ->groupBy('categories.id')
            ->get();
    }

    // возвращает ссылку на задачи по выбранной категории
    public function link() {
        if($this->parent_id == 0) {
            return route('typeOfProject', [
                'type_of_project' => $this->slug,
                'category_id' => $this->id,
            ]);
        } else {
            return route('category', [
                'type_of_project' => $this->parent->slug,
                'category' => $this->slug,
                'category_id' => $this->id,
            ]);
        }
    }

    public static function addCategory(int $parentId, string $categoryName):int {
        $newCategory = self::firstOrCreate([
            'title' => $categoryName,
            'parent_id' => $parentId,
            'slug' => str_slug($categoryName, '-'),
        ]);
        return $newCategory->id;
    }
}
