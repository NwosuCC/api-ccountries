<?php

use Carbon\Carbon;
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

$factory->define(App\User::class, function (Faker $faker) {

  return [
    'first_name' => $faker->firstName,
    'last_name' => $faker->lastName,

    // NOTE: Start time (-40years in this case) must evaluate to a date equal to or after "1970-01-01 00:00:00" (Epoch)
    // MySQL (version ---) will NOT accept any earlier date as "DateTime" value
    // 'date_of_birth' => $faker->dateTimeBetween('-40years', '-10years')->getTimestamp(), // seconds
    'date_of_birth' => Carbon::make( $faker->dateTimeBetween('-40years', '-10years'))->toDateTimeString(),

    'email' => $faker->unique()->safeEmail,
    'username' => title_case( str_replace('.', '', $faker->userName)),
    'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
  ];

});

$factory->state(App\User::class, 'raw_pass', function ($faker, $value) {
  return [
    'password' => bcrypt( 'secret' )
  ];
});
