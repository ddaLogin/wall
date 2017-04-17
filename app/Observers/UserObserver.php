<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 13.04.2017
 * Time: 14:09
 */

namespace App\Observers;


use App\Models\User;
use App\Repositories\UserRepository;

class UserObserver
{
    public function created(User $user)
    {
        (new UserRepository())->updateFullTextSearchField($user->id);
    }

    public function updated(User $user)
    {
        (new UserRepository())->updateFullTextSearchField($user->id);
    }
}