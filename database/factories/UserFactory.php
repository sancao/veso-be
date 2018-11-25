<?php

use Faker\Generator as Faker;
use App\Entities\Daily;
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

$factory->define(App\Entities\User::class, function (Faker $faker) {
    $dailies = Daily::pluck('id')->All();
    return [
        'name' => $faker->name,
        'daily_id'=>$faker->randomElement($dailies),
        'uuid'=> $faker->uuid,
        'address'=>$faker->address,
        'username'=>$faker->unique()->safeEmail,
        'email'=>$faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'email_verified_at' => $faker->dateTime(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'quyen'=>$faker->randomElement(['nhanvien', 'daily','banve']),
        'status'=>$faker->numberBetween(0, 1),
        'remember_token' => str_random(10),
    ];
});
