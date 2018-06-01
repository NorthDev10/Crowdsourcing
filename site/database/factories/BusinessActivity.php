<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        //
    ];
});


$factory->define(App\BusinessActivity::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->realText(50),
    ];
});