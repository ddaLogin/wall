<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace App\Services;


use App\Models\Post;
use App\Repositories\PostRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class PostService
{
    private $postRepository;
    private $userRepository;
    private $subscriptionRepository;

    /**
     * PostService constructor.
     * @param PostRepository $postRepository
     * @param UserRepository $userRepository
     * @param SubscriptionRepository $subscriptionRepository
     */
    public function __construct(PostRepository $postRepository, UserRepository $userRepository, SubscriptionRepository $subscriptionRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * publish post
     *
     * @param Request $request
     * @return Post
     */
    public function publish(Request $request)
    {
        return $this->postRepository->store($request->all(), Auth::user()->id);
    }

    /**
     * update post
     *
     * @param Post $post
     * @param Request $request
     * @return Post
     */
    public function update(Post $post, Request $request)
    {
        return $this->postRepository->store($request->all(), Auth::user()->id, $post);
    }

    /**
     * return all posts by text
     *
     * @param $q
     * @param $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search($q, $limit)
    {
        return $this->postRepository->search($q, $limit);
    }

    /**
     * return all feed posts
     *
     * @param $user_id
     * @param null $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function feedPosts($user_id, $limit = null)
    {
        $subscriptions = $this->subscriptionRepository->getByUser($user_id);
        return $this->postRepository->getByUsers($subscriptions->pluck('target_id'), $limit);
    }
}