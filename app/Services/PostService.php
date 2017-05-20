<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace App\Services;


use App\Interfaces\PostRepository;
use App\Interfaces\SearchRepository;
use App\Interfaces\SubscriptionRepository;
use App\Interfaces\UserRepository;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostService
{
    private $postRepository;
    private $userRepository;
    private $subscriptionRepository;
    private $searchRepository;

    /**
     * PostService constructor.
     * @param PostRepository $postRepository
     * @param UserRepository $userRepository
     * @param SubscriptionRepository $subscriptionRepository
     * @param SearchRepository $searchRepository
     */
    public function __construct(PostRepository $postRepository, UserRepository $userRepository, SubscriptionRepository $subscriptionRepository, SearchRepository $searchRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->searchRepository = $searchRepository;
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
        return $this->searchRepository->searchPosts($q, $limit);
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

    /**
     * return top of posts
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function topPosts($limit = 10)
    {
        return $this->postRepository->getTopPosts($limit);
    }
}