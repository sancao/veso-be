<?php

use Faker\Generator as Faker;
use App\Entities\User;
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

$factory->define(App\Entities\Chonso::class, function (Faker $faker) {
    $dailies = Daily::pluck('id')->All();
    $users = User::pluck('id')->All();
    return [
        'soduthuong' => $faker->numberBetween(10, 999999),
        'daily_id'=>$faker->randomElement($dailies),
        'user_id'=>$faker->randomElement($users),
        'tienduthuong'=>$faker->numberBetween(100, 999999),
        'loduthuong'=>$faker->numberBetween(1, 18),
        'daiduthuong'=>$faker->randomElement(['Long An', 'Tây Ninh', 'TPHCM','Tiền Giang','Vũng Tàu','Đồng Nai']),
        'mobile' => $faker->phoneNumber,
        'menhgia' => $faker->numberBetween(10, 999999),
        'menhgia10' => $faker->numberBetween(1, 100),
        'menhgia20' => $faker->numberBetween(1, 100),
        'menhgia50' => $faker->numberBetween(1, 100),
        'ngayduthuong'=>$faker->dateTime(),
        'hanmucconso'=>$faker->numberBetween(10, 100),
        'tonghanmuc'=>$faker->numberBetween(100, 999999)
    ];
});
