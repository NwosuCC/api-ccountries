<?php

use Faker\Generator as Faker;


$factory->define(App\Country::class, function (Faker $faker) {

  return [
    'name' => ($name = $faker->country),
  ];

});

$factory->state(App\Country::class, 'deleted_country', function ($faker) {
  return [
    'deleted_at' => Carbon\Carbon::now()->subSecond()
  ];
});

