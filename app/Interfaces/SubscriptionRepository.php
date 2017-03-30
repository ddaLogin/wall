<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:36
 */

namespace App\Interfaces;


use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface SubscriptionRepository
{
    /**
     * return all users subscriptions
     *
     * @param $user_id
     * @return Collection
     */
    public function getByUser($user_id);

    /**
     * return all target subscribers
     *
     * @param $target_id
     * @return Collection
     */
    public function getByTarget($target_id);

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
}