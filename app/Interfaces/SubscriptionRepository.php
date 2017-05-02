<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:36
 */

namespace App\Interfaces;


use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;

interface SubscriptionRepository
{
    /**
     * return users subscriptions
     *
     * @param $user_id
     * @param int $limit
     * @return Collection
     */
    public function getByUser($user_id, $limit = null);

    /**
     * return target subscribers
     *
     * @param $target_id
     * @param int $limit
     * @return Collection
     */
    public function getByTarget($target_id, $limit = null);

    /**
     * return subscription by user and target
     *
     * @param $user_id
     * @param $target_id
     * @return Subscription
     */
    public function getByUserAndTarget($user_id, $target_id);

    /**
     * store subscription
     *
     * @param $data
     * @return Subscription
     */
    public function store($data);

    /**
     * delete subscription
     *
     * @param $id
     * @return boolean
     */
    public function delete($id);

    /**
     * return all friends of user
     * friend - user's who has mutual subscription to this user
     *
     * @param $user_id
     * @return Collection
     */
    public function friends($user_id);
}