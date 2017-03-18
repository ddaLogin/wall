<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace app\Services;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService
{
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
        $user = new User($request->all());
        $user->password = bcrypt($user->password);
        $user->save();

        return $user;
    }
}