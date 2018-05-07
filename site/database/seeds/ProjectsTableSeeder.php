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
        
        factory(App\Project::class, 5)->create()->each(function($p) 
                use ($allSkils, $allUsers) {
            $faker = Faker\Factory::create();
            $numSkills = $faker->unique()->numberBetween(1, 5);
            // генерируем id скилов
            $skillIDList = [];
            for($i = 0; $i < $numSkills; ++$i) {
                $skillIDList[] = $faker->unique()->numberBetween(1, $allSkils);
            }
            // генерируем необходимые навыки
            $p->necessarySkills()->attach($skillIDList);
            
            // пользователи комментируют
            $comments = [];
            for($i = 0; $faker->numberBetween(0, 10); ++$i) {
                $comment = new App\Comment();
                $comment->project_id = $p->id;
                $comment->user_id = $faker->numberBetween(1, $allUsers);
                $comment->description = $faker->realText(50);
                $comments[] = $comment;
            }

            $p->comments()->saveMany($comments);
            
            $faker = Faker\Factory::create();

            // желающие поучаствовать в проекте
            $users = [];
            for($i = 0; $faker->numberBetween(0, 10); ++$i) {
                $pe = new App\ProjectExecutor();
                $pe->project_id = $p->id;
                $pe->user_id = $faker->unique()->numberBetween(1, $allUsers);
                $pe->user_selected = $faker->boolean;
                $users[] = $pe;
            }
            $p->projectExecutors()->saveMany($users);

            // исполнители проекта общаются в чате
            $messageList = [];
            for($i = 0; $i < count($users); ++$i) {
                if($users[$i]->user_selected) {
                    $numMessage = $faker->numberBetween(0, 5);
                    for($j = 0; $j < $numMessage; ++$j) {
                        $message = new App\GroupChat();
                        $message->project_id = $p->id;
                        $message->user_id = $users[$i]->user_id;
                        $message->message = $faker->realText(25);
                        $messageList[] = $message;
                    }
                }
            }

            $p->projectExecutors()
                ->first()
                ->groupChats()
                ->saveMany($messageList);

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
