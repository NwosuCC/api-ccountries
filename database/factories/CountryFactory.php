<?php

use App\Continent;
use App\Country;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;


$factory->define(Country::class, function (Faker $faker) {

  return [
    'name' => $faker->country,
    'continent' => factory(Continent::class)->make()->name,
  ];

});

$factory->state(Country::class, 'created_by_me', function ($faker) {
  return [
    'user_id' => auth()->id()
  ];
});

$factory->state(Country::class, 'created_by_other_users', function ($faker) {
  return [
    'user_id' => factory(User::class)->create()->id,
  ];
});

