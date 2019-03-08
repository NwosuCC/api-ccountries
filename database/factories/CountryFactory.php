<?php

use App\Continent;
use App\Country;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;


$factory->define(Country::class, function (Faker $faker) {

  return [
    'name' => $faker->country,
  ];

});


// Country user_id === auth()-id()
$factory->state(Country::class, 'created_by_me', function ($faker) {
  return [
    'user_id' => auth()->id()
  ];
});


// Country user_id === randomlyCreatedUser->id
$factory->state(Country::class, 'created_by_other_users', function ($faker) {
  return [
    'user_id' => factory(User::class)->create()->id,
  ];
});


// Eloquent ORM: DB stores 'continent_id' in countries table
$factory->state(Country::class, 'for_db_store', function ($faker) {
  return [
    'continent_id' => factory(Continent::class)->raw()['id'],
  ];
});


// Http Request: Api supplies 'continent' in the Create form
$factory->state(Country::class, 'for_http_api', function ($faker) {
  return [
    'continent' => factory(Continent::class)->raw()['name'],
  ];
});

