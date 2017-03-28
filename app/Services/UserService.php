<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace App\Services;


use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService
{
    private $userRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Authenticate user
     *
     * @param Request $request
     * @return bool
     */
    public function auth(Request $request)
    {
        return Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]);
    }

    /**
     *  Log out user
     */
    public function logout()
    {
        Auth::logout();
    }

    /**
     * Registration user
     *
     * @param Request $request
     * @return User
     */
    public function registration(Request $request)
    {
        return $this->userRepository->store($request->all());
    }

    /**
     * return all users, by nickname
     *
     * @param $q
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search($q)
    {
        return $this->userRepository->searchByNickname($q);
    }
}