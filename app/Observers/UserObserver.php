<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 13.04.2017
 * Time: 14:09
 */

namespace App\Observers;


use App\Interfaces\UserRepository;
use App\Models\User;

class UserObserver
{
    private $userRepository;

    /**
     * UserObserver constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function created(User $user)
    {
        $this->userRepository->updateFullTextSearchField($user->id);
    }

    public function updated(User $user)
    {
        $this->userRepository->updateFullTextSearchField($user->id);
    }
}