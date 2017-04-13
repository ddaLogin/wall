<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 26.03.2017
 * Time: 20:50
 */

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * search user by nickname
     *
     * @param $q
     * @return Collection
     */
    public function searchByNickname($q)
    {
        return User::where('nickname', 'LIKE', "%{$q}%")->take(7)->get();
    }

    /**
     * update user photo
     *
     * @param $user_id
     * @param $url
     * @param $urlMini
     * @return mixed
     */
    public function updatePhoto($user_id, $url, $urlMini)
    {
        $user = $this->getById($user_id);
        $user->photo = $url;
        $user->photo_mini = $urlMini;
        $user->save();

        return true;
    }
}