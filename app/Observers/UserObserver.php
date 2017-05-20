<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 13.04.2017
 * Time: 14:09
 */

namespace App\Observers;

use App\Interfaces\SearchRepository;
use App\Models\User;

class UserObserver
{
    private $searchRepository;

    /**
     * UserObserver constructor.
     * @param SearchRepository $searchRepository
     */
    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function created(User $user)
    {
        $this->searchRepository->updateFullTextSearchUsersField($user->id);
    }

    public function updated(User $user)
    {
        $this->searchRepository->updateFullTextSearchUsersField($user->id);
    }
}