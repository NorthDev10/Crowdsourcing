<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Генерим пользователей и их скилы
     *
     * @return void
     */
    public function run()
    {
        $allSkils = App\Skill::count();

        factory(App\User::class, 50)->create()->each(function($u) use ($allSkils) {
            $faker = Faker\Factory::create();
            $numSkills = $faker->unique()->numberBetween(1, 10);
            // генерируем id скилов
            $skillIDList = [];
            for($i = 0; $i < $numSkills; ++$i) {
                $skillIDList[] = $faker->unique()->numberBetween(1, $allSkils);
            }
            // у каждого пользователя будет свой набор скилов
            $u->skills()->attach($skillIDList);
        });
    }
}
