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

$factory->define(App\Entities\Daily::class, function (Faker $faker) {
    
    return [
        'tendaily' => $faker->name,
        'uuid'=> $faker->uuid,
        'madaily'=>$faker->unique()->safeEmail,
        'dailyquanly'=>1,
        'diachi'=>$faker->address,
        'sodienthoai' => $faker->phoneNumber,
        'cap' => $faker->randomElement(['cap1', 'cap2','cap3','cap4']),
    ];
});
