<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;


class Country extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;


    protected $fillable = [
        'name', 'continent_id', 'user_id'
    ];

    protected $hidden = [
      'deleted_at', 'updated_at', 'user_id', 'continent_id'
    ];

    protected $appends = [
      'continent'
    ];


    public function continent(){
      return $this->belongsTo(Continent::class)->latest();
    }


    public function getContinentAttribute(){
      return $this->continent()->first();
    }


}
