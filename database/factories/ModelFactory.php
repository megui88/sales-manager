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
    $role = $roles[array_rand($roles)];
    return [
        'code' => $faker->numberBetween(),
        'name' => $faker->name,
        'last_name' => $faker->lastName,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'enable' => rand(0,1),
        'administrative_expenses' => (\App\Services\BusinessCore::VENDOR_ROLE == $role) ? rand(5,10) : 0,
        'role' => $role
    ];
});

$factory->define(App\OauthClients::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->randomElement(['web', 'mobile', 'vendor']),
        'secret' => bcrypt(str_random(10)),
        'name' => $faker->name,
    ];
});

$factory->define(App\Periods::class, function (Faker\Generator $faker) {
    $dueDate = $faker->dateTimeBetween('-3 years');
    $period = \App\Services\BusinessCore::dateToPeriodFormat($dueDate);
    $user = factory(\App\User::class)->create(['role' => \App\Services\BusinessCore::EMPLOYEE_ADMIN_ROLE]);
    return [
        'uid' => $period,
        'due_date' => $dueDate,
        'operator_id_opened' => $user->id,
    ];
});

$factory->define(App\Groups::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->domainName,
        'description' => $faker->text,
    ];
});
