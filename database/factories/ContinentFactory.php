<?php

use App\Continent;
use Faker\Generator as Faker;


$factory->define(Continent::class, function (Faker $faker) {

  $continent = ($all = Continent::all()) && $all->count() ? $all->random() : null;

  return [
    'name' => $continent ? $continent->{'name'} : null,
    'id' => $continent ? $continent->{'id'} : null,
  ];

});

$factory->state(Continent::class, 'seeding', function ($faker) {
  return [
    'id' => 0
  ];
});