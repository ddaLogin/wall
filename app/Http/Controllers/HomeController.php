<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Repositories\PostRepository;
use App\Services\UserService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $postRepository;

    /**
     * HomeController constructor.
     * @param $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * return home page
     */
    public function index()
    {
        $posts = $this->postRepository->getTopPosts();
        return view('home')->with([
            'posts' => $posts
        ]);
    }

    /**
     * return log in page
     */
    public function login()
    {
        return view('login');
    }

    /**
     * log out page
     * @param UserService $userService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(UserService $userService)
    {
        $userService->logout();
        return redirect()->route('login');
    }

    /**
     * return sign up page
     */
    public function signup()
    {
        return view('signup');
    }

    /**
     * registration new user
     *
     * @param UserStoreRequest $request
     * @param UserService $userService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registration(UserStoreRequest $request, UserService $userService)
    {
        $userService->registration($request);
        return redirect()->route('login');
    }

    /**
     * authenticate user
     *
     * @param Request $request
     * @param UserService $userService
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request, UserService $userService)
    {
        if ($userService->auth($request)){
            return redirect()->intended();
        } else {
            return redirect()->back()->withInput()->withErrors(['authenticate' => 'Incorrect email or password']);
        }
    }
}
