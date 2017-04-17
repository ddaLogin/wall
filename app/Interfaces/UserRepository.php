<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:36
 */

namespace App\Interfaces;


use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepository
{
    /**
     * return user by id
     *
     * @param $id
     * @return User
     */
    public function getById($id);

    /**
     * return user by nickname
     *
     * @param $nickname
     * @return User
     */
    public function getByNickname($nickname);

    /**
     * store new user
     *
     * @param $data
     * @return User
     */
    public function store($data);

    /**
     * search users by nickname or mails
     *
     * @param $q
     * @return Collection
     */
    public function search($q);

    /**
     * update user photo
     *
     * @param $user_id
     * @param $url
     * @param $urlMini
     * @return mixed
     */
    public function updatePhoto($user_id, $url, $urlMini);
}