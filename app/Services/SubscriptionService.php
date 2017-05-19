<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace App\Services;

use App\Interfaces\SubscriptionRepository;
use App\Interfaces\UserRepository;
use App\Notifications;
use Illuminate\Support\Facades\Notification;

class SubscriptionService
{
    private $subscriptionRepository;
    private $userRepository;

    /**
     * UserService constructor.
     * @param SubscriptionRepository $subscriptionRepository
     * @param UserRepository $userRepository
     */
    public function __construct(SubscriptionRepository $subscriptionRepository, UserRepository $userRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->userRepository = $userRepository;
    }


    /**
     * toggle subscription and return true, if subscription active, false when not
     * @param $user_id
     * @param $target_id
     * @return bool
     */
    public function toggleSubscription($user_id, $target_id)
    {
        $user = $this->userRepository->getById($user_id);
        $target = $this->userRepository->getById($target_id);
        if ($subscription = $this->subscriptionRepository->getByUserAndTarget($user_id, $target_id)){
            $this->subscriptionRepository->delete($subscription->id);
            Notification::send($target, new Notifications\UserUnsubscribed($user));
            return false;
        } else {
            $this->subscriptionRepository->store(['user_id' => $user_id, 'target_id' => $target_id]);
            Notification::send($target, new Notifications\UserSubscribed($user));
            return true;
        }
    }
}