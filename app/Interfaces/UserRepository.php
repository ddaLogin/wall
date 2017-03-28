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
     * search user by nickname
     *
     * @param $q
     * @return Collection
     */
    public function searchByNickname($q);
}