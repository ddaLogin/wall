<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 26.03.2017
 * Time: 20:50
 */

namespace App\Repositories;

use App\Models\User;

class UserRepository implements \App\Interfaces\UserRepository
{
    /**
     * return user by id
     *
     * @param $id
     * @return User
     */
    public function getById($id)
    {
        return User::findorfail($id);
    }

    /**
     * return user by nickname
     *
     * @param $nickname
     * @return User
     */
    public function getByNickname($nickname)
    {
        return User::where('nickname', $nickname)->first();
    }

    /**
     * store new user
     *
     * @param $data
     * @return User
     */
    public function store($data)
    {
        $user = new User($data);
        $user->password = bcrypt($user->password);
        $user->save();

        return $user;
    }
}