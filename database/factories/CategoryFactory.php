<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {
    $time=$faker->dateTime;
    return [
        'name'=>$faker->name,
        'desc'=>$faker->sentence,
        'created_at'=>$time,
        'updated_at'=>$time
    ];
});
