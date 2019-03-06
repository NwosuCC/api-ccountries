<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;


class Country extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;


    protected $fillable = [
        'name', 'continent', 'user_id'
    ];

    protected $hidden = [
      'deleted_at', 'updated_at', 'user_id'
    ];

    protected static $preferred_case = 'title_case';

    private static $continents = [
      'Africa', 'Antarctica', 'Asia', 'Australia', 'Europe', 'North America', 'South America'
    ];

    // Prefer id
//    public function getRouteKeyName()
//    {
//      return 'name';
//    }

    // NOTE: ContinentTitle::class middleware takes care of this. Just in case it missed, though
    public function setContinentAttribute($value)
    {
      $this->attributes['continent'] = title_case($value);
    }

    public static function to_preferred_case($value) {
      return call_user_func( static::$preferred_case, $value);
    }

    public static function continents()
    {
        return array_map(function($value){ return static::to_preferred_case($value); }, static::$continents);
    }


}
