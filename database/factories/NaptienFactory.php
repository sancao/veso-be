<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Entities\Naptien::class, function (Faker $faker) {
    return [
        'tendaily' => $faker->name,
        'uuid'=> $faker->uuid,
        'sotien'=>$faker->numberBetween(100, 999999),
        'ngaynap'=>$faker->dateTime(),
        'trangthai'=>$faker->numberBetween(0, 1)
    ];
});
