<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allSkils = App\Skill::count();
        $allUsers = App\User::count();
        $allCategories = App\Category::count();
        
        factory(App\Project::class, 20)->create()->each(function($p) 
                use ($allSkils, $allUsers, $allCategories) {
            $faker = Faker\Factory::create();
            $categoryList = App\Category::where('id', $p->type_project_id)->first()->children;

            // генерируем таски
            $subtaskList = [];
            for($i = 0; $i < $faker->numberBetween(1, 5); ++$i) {
                $subtask = new App\Subtask();
                $subtask->project_id = $p->id;
                $subtask->category_id = $categoryList[$faker->numberBetween(0, count($categoryList)-1)]->id;
                $subtask->task_name = $faker->realText(15);
                $subtask->number_executors = $faker->numberBetween(1, 4);
                $subtask->description = $faker->realText(50);
                $subtask->status = $faker->boolean;
                $subtaskList[] = $subtask;
            }

            $categoryList = NULL;

            $p->subtasks()->saveMany($subtaskList);

            
            
            $users = [];
            foreach($subtaskList as $subtask) {
                // желающие поучаствовать в проекте
                for($i = 0; $i < $subtask->number_executors; $i++) { 
                    $user = new App\TaskExecutor();
                    $user->subtask_id = $subtask->id;
                    $user->user_id = $faker->numberBetween(1, $allUsers);
                    $subtask->involved_executors++;
                    $user->comment = $faker->realText(50);
                    $user->user_selected = $faker->boolean;
                    $user->save();
                    $users[] = $user;
                }

                $faker = Faker\Factory::create();
                $numSkills = $faker->unique()->numberBetween(1, 5);
                // генерируем id скилов
                $skillIDList = [];
                for($i = 0; $i < $numSkills; ++$i) {
                    $skillIDList[] = $faker->unique()->numberBetween(1, $allSkils);
                }
                // записываем необходимые навыки
                $subtask->necessarySkills()->attach($skillIDList);
            }

            // если проект завершен, то генерируем отзывы
            if($p->status == 'closed') {
                $reviewList = [];
                for($i = 0; $i < count($users); ++$i) {
                    if($users[$i]->user_selected) {
                        // отзыв от заказчика
                        $review = new App\Review();
                        $review->project_id = $p->id;
                        $review->reviewer_id = $p->user_id;
                        $review->user_id = $users[$i]->user_id;
                        $review->description = 'Отличный исполнитель, выполнил проект быстро и качественно. Мои лучшие рекомендации!';
                        $reviewList[] = $review;

                        // отзыв от исполнителя
                        $review = new App\Review();
                        $review->project_id = $p->id;
                        $review->reviewer_id = $users[$i]->user_id;
                        $review->user_id = $p->user_id;
                        $review->description = 'Написал грамотное ТЗ. Никаких проблем при сотрудничестве не возникло. На любые вопросы отвечает быстро. Мои лучшие рекомендации!';
                        $reviewList[] = $review;
                    }
                }
                $p->reviews()->saveMany($reviewList);
            }
        });
    }
}
