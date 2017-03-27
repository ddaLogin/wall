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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class PostService
{
    private $postRepository;

    /**
     * PostService constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * publish post
     *
     * @param Request $request
     * @return Post
     */
    public function publish(Request $request)
    {
        if (Auth::guest() || !Auth::user()->can('create', new Post())){
            throw new AccessDeniedException("Could not create post");
        }

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
        if(Auth::guest() || !Auth::user()->can('update', $post)){
            throw new AccessDeniedException("Could not update post - {$post->id}");
        }

        return $this->postRepository->store($request->all(), Auth::user()->id, $post);
    }
}