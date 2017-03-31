<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userRepository;
    private $postRepository;
    private $subscriptionRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     * @param PostRepository $postRepository
     * @param SubscriptionRepository $subscriptionRepository
     */
    public function __construct(UserRepository $userRepository, PostRepository $postRepository, SubscriptionRepository $subscriptionRepository)
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * return page with all user's posts
     *
     * @param Request $request
     * @param $nickname
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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

    /**
     * return page with user subscriptions and subscribers
     *
     * @param Request $request
     * @return $this
     */
    public function subscriptions(Request $request)
    {
        return view('user.subscriptions')->with([
            'subscriptions' => $this->subscriptionRepository->getByUser(Auth::user()->id),
            'subscribers' => $this->subscriptionRepository->getByTarget(Auth::user()->id),
        ]);
    }
}
