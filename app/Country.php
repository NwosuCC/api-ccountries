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

}
