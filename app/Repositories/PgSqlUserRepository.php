<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 26.03.2017
 * Time: 20:50
 */

namespace App\Repositories;

use App\Interfaces\UserRepository;
use App\Models\User;

class PgSqlUserRepository implements UserRepository
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
     * store user
     *
     * @param $data
     * @param null $user_id
     * @return User
     */
    public function store($data, $user_id = null)
    {
        $user = ($user_id)?$this->getById($user_id):new User($data);
        $user->fill($data);

        if (key_exists('password', $data)){
            $user->password = $data['password'];
        }

        if (key_exists('photo', $data)){
            $user->photo = $data['photo'];
        }

        if (key_exists('photo_mini', $data)){
            $user->photo_mini = $data['photo_mini'];
        }

        $user->save();

        return $user;
    }
}