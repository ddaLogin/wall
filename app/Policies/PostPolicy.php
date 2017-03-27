<?php

namespace App\Policies;

use App\Models;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
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

    public function create(Models\User $user, Models\Post $post)
    {
        return ($user && $user->id);
    }

    public function update(Models\User $user, Models\Post $post)
    {
        return ($user && $user->id == $post->author_id);
    }
}
