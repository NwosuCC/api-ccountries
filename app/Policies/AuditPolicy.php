<?php

namespace App\Policies;

use App\Audit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuditPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        //
    }*/

    /**
     * Determine whether to override all other checks for this user
     *
     * @param  \App\User  $user
     * @param  $ability [The action to perform e.g 'update']
     * @return mixed
     */
    public function before(User $user, $ability)
    {
      return $user->isAdmin();
    }


    public function create(User $user)
    {
      //
    }

    public function view(User $user, Audit $audit)
    {
      //
    }

}
