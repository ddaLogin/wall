<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 28.03.2017
 * Time: 21:33
 */

namespace App\Repositories;


use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionRepository implements \App\Interfaces\SubscriptionRepository
{

    /**
     * return all users subscriptions
     *
     * @param $user_id
     * @return Collection
     * @internal param User $user
     */
    public function getByUser($user_id)
    {
        return Subscription::where('user_id', $user_id)->with(['user', 'target'])->get();
    }

    /**
     * return all
     *
     * @param $target_id
     * @return Collection
     * @internal param User $target
     */
    public function getByTarget($target_id)
    {
        return Subscription::where('target_id', $target_id)->with(['user', 'target'])->get();
    }

    /**
     * return subscription by user and target
     *
     * @param $user_id
     * @param $target_id
     * @return Subscription
     */
    public function getByUserAndTarget($user_id, $target_id)
    {
        return Subscription::where('user_id', $user_id)
            ->where('target_id', $target_id)
            ->first();
    }

    /**
     * store subscription
     *
     * @param $data
     * @return Subscription
     */
    public function store($data)
    {
        $subscription = new Subscription();
        $subscription->user_id = $data['user_id'];
        $subscription->target_id = $data['target_id'];
        $subscription->save();
        return $subscription;
    }

    /**
     * delete subscription
     *
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        Subscription::where('id', $id)->delete();
        return true;
    }
}