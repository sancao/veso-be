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

$factory->define(App\Entities\Chonso::class, function (Faker $faker) {
    return [
        'soduthuong' => $faker->numberBetween(10, 999999),
        'uuid'=> $faker->uuid,
        'tienduthuong'=>$faker->numberBetween(100, 999999),
        'loduthuong'=>$faker->numberBetween(1, 18),
        'daiduthuong'=>$faker->randomElement(['Long An', 'Tây Ninh', 'TPHCM','Tiền Giang','Vũng Tàu','Đồng Nai']),
        'mobile' => $faker->phoneNumber,
        'menhgia' => $faker->numberBetween(10, 999999),
        'ngayduthuong'=>$faker->dateTime(),
        'hanmucconso'=>$faker->numberBetween(10, 100),
        'tonghanmuc'=>$faker->numberBetween(100, 999999)
    ];
});
