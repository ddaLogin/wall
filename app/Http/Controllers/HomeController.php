<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * return home page
     */
    public function index()
    {
        return view('home.index');
    }

    /**
     * return log in page
     */
    public function login()
    {
        return view('home.login');
    }

    /**
     * return sign up page
     */
    public function signup()
    {
        return view('home.signup');
    }

    public function registration(UserStoreRequest $request, UserService $userService)
    {
        $userService->registration($request);
        return redirect()->route('home.login');
    }

    public function authenticate(Request $request, UserService $userService)
    {
        if ($userService->auth($request)){
            return redirect()->intended();
        } else {
            return redirect()->back()->withInput()->withErrors(['authenticate' => 'Incorrect email or password']);
        }
    }
}
