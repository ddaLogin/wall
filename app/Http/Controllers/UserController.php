<?php

namespace App\Http\Controllers;

use App\Models;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userRepository;
    private $postRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     * @param PostRepository $postRepository
     */
    public function __construct(UserRepository $userRepository, PostRepository $postRepository)
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * return page with all user's posts
     *
     * @param Request $request
     * @param $nickname
     * @return mixed
     */
    public function wall(Request $request, $nickname)
    {
        $user = $this->userRepository->getByNickname($nickname);
        $posts = $this->postRepository->getByAuthorId($user->id);

        return view('user.wall')->with([
            'user' => $user,
            'posts' => $posts,
        ]);
    }
}
