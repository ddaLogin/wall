<?php

namespace App\Policies;

use App\Models;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function subscribe(Models\User $user, Models\User $target)
    {
        return ($user && $user->id != $target->id);
    }
}
