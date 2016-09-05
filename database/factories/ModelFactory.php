<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {

    $roles = [\App\Services\BusinessCore::MEMBER_ROLE, \App\Services\BusinessCore::VENDOR_ROLE];
    return [
        'code' => $faker->numberBetween(),
        'name' => $faker->name,
        'last_name' => $faker->lastName,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'enable' => rand(0,1),
        'role' => $roles[array_rand($roles)]
    ];
});

$factory->define(App\OauthClients::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->randomElement(['web', 'mobile', 'vendor']),
        'secret' => bcrypt(str_random(10)),
        'name' => $faker->name,
    ];
});
