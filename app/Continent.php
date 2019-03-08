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
    'deleted_at', 'updated_at', 'user_id'
  ];

  /**
   * @var callable $preferred_case ['strtoupper', 'strtolower', 'title_case']
   */
  protected static $preferred_case = 'title_case';

  protected static $seed;


  public function countries(){
    return $this->hasMany(Country::class);
  }


  public function addCountry(Country $country){
    $country->setAttribute('continent_id', $this->id);
    return $country;
  }


  public function scopeFromName($query, $input){
    return $query->where('name', $input)->first();
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

      static::$seed = array_map(function ($value) { return static::to_preferred_case($value); }, $continents);
    }

    return static::$seed;
  }

}