<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'date_of_birth', 'email', 'username', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at', 'updated_at',
    ];


    public static function tokenName() {
      return 'Countries User Token';
    }

    /*public function getRouteKeyName() {
        return 'username';
    }*/

    public static function minBirthDate() {
      return now()->subYearsNoOverflow(10)->toDateString();
    }

    public function isAdmin(){
      return trim( strtolower( stristr($this->email, '@', true))) === 'admin';
    }


}
