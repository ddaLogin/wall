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
     * @param int $limit
     * @return Collection
     */
    public function search($q, $limit = null);

    /**
     * update user photo
     *
     * @param $user_id
     * @param $url
     * @param $urlMini
     * @return mixed
     */
    public function updatePhoto($user_id, $url, $urlMini);

    /**
     * change user email
     *
     * @param $user_id
     * @param $email
     * @return mixed
     */
    public function changeMail($user_id, $email);

    /**
     * change user password
     *
     * @param $user_id
     * @param $password
     * @return mixed
     */
    public function changePassword($user_id, $password);
}