<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;


class Continent extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;


  protected $fillable = [
    'name', 'slug'
  ];

  protected $hidden = [
    'deleted_at', 'created_at', 'updated_at', 'user_id'
  ];

  /**
   * @var callable $preferred_case ['strtoupper', 'strtolower', 'title_case']
   */
  protected static $preferred_case = 'title_case';

  protected static $seed;


  public function countries(){
    return $this->hasMany(Country::class)->latest();
  }


  public function addCountry(Country $country, string $continent_name = null)
  {
    $continent = ($continent_name) ? Continent::fromName($continent_name) : $this;

    $country->setAttribute('continent_id', $continent->id);

    return $country;
  }


  /**
   * @param $input
   * @return \App\Continent
   */
  public static function fromName($input){
    return Continent::where('name', $input)->first();
  }


  public static function to_preferred_case($value)
  {
    return call_user_func(static::$preferred_case, $value);
  }


  public static function seed()
  {
    if( ! static::$seed){
      $continents = [
        'Africa', 'Antarctica', 'Asia', 'Australia', 'Europe', 'North America', 'South America'
      ];

      static::$seed = array_map(function ($value) {
        return static::to_preferred_case($value);
      }, $continents);
    }

    return static::$seed;
  }

}