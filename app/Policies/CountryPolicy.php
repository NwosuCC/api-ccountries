<?php

namespace App\Policies;

use App\User;
use App\Country;
use Illuminate\Auth\Access\HandlesAuthorization;


/**
 * NOTICE: Register Policy in AuthServiceProvider $policies
 *    'App\Country' => 'App\Policies\CountryPolicy',
 */
class CountryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether to override all other checks for this user
     *
     * @param  \App\User  $user
     * @param  $ability [The action to perform e.g 'update']
     * @return mixed
     */
    public function before(User $user, $ability)
    {
        //
    }

    /**
     * Determine whether the user can view the country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function view(User $user, Country $country)
    {
        //
    }

    /**
     * Determine whether the user can create countries.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function update(User $user, Country $country)
    {
        return $user->id === $country->user_id;
    }

    /**
     * Determine whether the user can delete the country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function delete(User $user, Country $country)
    {
        return $user->id === $country->user_id;
    }

    /**
     * Determine whether the user can restore the country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function restore(User $user, Country $country)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function forceDelete(User $user, Country $country)
    {
        return false;
    }
}
