<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    $allUsers = App\User::count();
    $allCategories = App\Category::count();

    $tenderClosing = $faker->dateTimeBetween('now', '+4 days');

    return [
        'user_id' => $faker->numberBetween(1, $allUsers),
        'category_id' => $faker->numberBetween(1, $allCategories),
        'project_name' =>  $faker->realText(50),
        'project_description' => $faker->realText(1000),
        'status' => $faker->boolean,
        'deadline' => $faker->dateTimeBetween($tenderClosing, '+4 weeks'),
        'tender_closing' => $tenderClosing,
    ];
});
