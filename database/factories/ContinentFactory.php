<?php

use App\Continent;
use Faker\Generator as Faker;


$factory->define(Continent::class, function (Faker $faker) {

  return [
    'name' => (($all = Continent::all()) && $all->count()) ? $all->random()->{'name'} : null,
  ];

});
