<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace App\Services;

use App\Repositories\SubscriptionRepository;

class SubscriptionService
{
    private $subscriptionRepository;

    /**
     * UserService constructor.
     * @param SubscriptionRepository $subscriptionRepository
     */
    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }


    /**
     * toggle subscription and return true, if subscription active, false when not
     * @param $user_id
     * @param $target_id
     * @return bool
     */
    public function toggleSubscription($user_id, $target_id)
    {
        if ($subscription = $this->subscriptionRepository->getByUserAndTarget($user_id, $target_id)){
            $this->subscriptionRepository->delete($subscription->id);
            return false;
        } else {
            $this->subscriptionRepository->store(['user_id' => $user_id, 'target_id' => $target_id]);
            return true;
        }
    }
}