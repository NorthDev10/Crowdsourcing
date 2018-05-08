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
        
        factory(App\Project::class, 5)->create()->each(function($p) 
                use ($allSkils, $allUsers, $allCategories) {
            $faker = Faker\Factory::create();
            $numSkills = $faker->unique()->numberBetween(1, 5);
            // генерируем id скилов
            $skillIDList = [];
            for($i = 0; $i < $numSkills; ++$i) {
                $skillIDList[] = $faker->unique()->numberBetween(1, $allSkils);
            }
            // записываем необходимые навыки
            $p->necessarySkills()->attach($skillIDList);
            
            $faker = Faker\Factory::create();

            // генерируем таски
            $subtaskList = [];
            for($i = 0; $i < $faker->numberBetween(1, 5); ++$i) {
                $subtask = new App\Subtask();
                $subtask->project_id = $p->id;
                $subtask->category_id = $faker->unique()->numberBetween(1, $allCategories);
                $subtask->number_executors = $faker->numberBetween(1, 4);
                $subtask->description = $faker->realText(50);
                $subtask->status = $faker->boolean;
                $subtaskList[] = $subtask;
            }

            $p->subtasks()->saveMany($subtaskList);

            
            // желающие поучаствовать в проекте
            $users = [];
            foreach($subtaskList as $subtask) {
                for($i = 0; $i < $subtask->number_executors; $i++) { 
                    $user = new App\TaskExecutor();
                    $user->subtask_id = $subtask->id;
                    $user->user_id = $faker->numberBetween(1, $allUsers);
                    $user->comment = $faker->realText(50);
                    $user->user_selected = $faker->boolean;
                    $user->save();
                    $users[] = $user;
                }
            }

            // если проект завершен, то генерируем отзывы
            if($p->status == false) {
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
