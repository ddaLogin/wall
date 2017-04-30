<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Services\PostService;
use App\Repositories\PostRepository;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $posts = $this->postRepository->getTopPosts(config('values.home.topPostsLimit'));
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
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
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $request->input('remember'))){
            return redirect()->intended();
        } else {
            return redirect()->back()->withInput()->withErrors(['authenticate' => 'Incorrect email or password']);
        }
    }

    /**
     * return all users by nickname
     *
     * @param Request $request
     * @param UserService $userService
     * @param PostService $postService
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request, UserService $userService, PostService $postService)
    {
        if ($request->ajax()){
            $data['users'] = $userService->search($request->input('q'), config('values.fastSearchLimit'));
            $data['posts'] = $postService->search($request->input('q'), config('values.fastSearchLimit'));
            return response()->json($data,200);
        } else {
            return view('search')->with([
                'users' => $userService->search($request->input('q'), null),
                'posts' => $postService->search($request->input('q'), null),
                'q' => $request->input('q'),
            ]);
        }
    }
}
