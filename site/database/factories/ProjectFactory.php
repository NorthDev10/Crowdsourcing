<?php

use Faker\Generator as Faker;
use \Carbon\Carbon;

$factory->define(App\Project::class, function (Faker $faker) {
    $allUsers = App\User::count();
    $allCategories = App\Category::count();
    $allBusinessActivities = App\BusinessActivity::count();

    $project_name = $faker->realText(50);
    $date = Carbon::now()->format('d-m-Y-');
    $status = ['opened', 'performed', 'closed'];
    $categoryList = App\Category::where('parent_id', 0)->get();
    $tenderClosing = $faker->numberBetween(0, 6);
    
    return [
        'user_id' => $faker->numberBetween(1, $allUsers),
        'type_project_id' => $categoryList[$faker->numberBetween(0, count($categoryList)-1)]->id,
        'project_name' =>  $project_name,
        'brand' => $faker->realText(50),
        'business_activity_id' => $faker->numberBetween(0, $allBusinessActivities-1),
        'project_description' => $faker->realText(1000),
        'slug' => $date.str_slug($project_name, '-'),
        'status' => 'opened',//$status[$faker->numberBetween(0, count($status)-1)],
        'deadline' => $faker->dateTimeBetween(
            date_modify(new DateTime(), '+'.$tenderClosing.' day'), 
            '+4 weeks'
        ),
        'tender_closing' => $tenderClosing,
    ];
});
