<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 28.03.2017
 * Time: 21:33
 */

namespace App\Repositories;


use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PgSqlSubscriptionRepository implements \App\Interfaces\SubscriptionRepository
{

    /**
     * return users subscriptions
     *
     * @param $user_id
     * @param int $limit
     * @return Collection
     * @internal param User $user
     */
    public function getByUser($user_id, $limit = null)
    {
        $query = Subscription::where('user_id', $user_id)->with(['user', 'target']);

        if($limit){
            $query = $query->take($limit);
        }

        return $query->get();
    }

    /**
     * return target subscribers
     *
     * @param $target_id
     * @param int $limit
     * @return Collection
     * @internal param User $target
     */
    public function getByTarget($target_id, $limit = null)
    {
        $query = Subscription::where('target_id', $target_id)->with(['user', 'target']);

        if($limit){
            $query = $query->take($limit);
        }

        return $query->get();
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

    /**
     * return all friends of user
     * friend - user's who has mutual subscription to this user
     *
     * @param $user_id
     * @return Collection - of class Models\User
     */
    public function friends($user_id)
    {
        $subscriptions = (new PgSqlSubscriptionRepository())->getByUser($user_id);
        $subscribers = (new PgSqlSubscriptionRepository())->getByTarget($user_id);
        $friends = User::whereIn('id', $subscriptions->pluck('target_id'))
            ->whereIn('id', $subscribers->pluck('user_id'))
            ->get();

        return $friends;
    }
}