<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PostController extends Controller
{
    /**
     * return page to create new post
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('post.create')->with(['post' => new Post()]);
    }

    /**
     * store new post
     *
     * @param PostStoreRequest $request
     * @param PostService $postService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PostStoreRequest $request, PostService $postService)
    {
        $post = $postService->publish($request);
        return redirect()->route('post.show', $post);
    }

    /**
     * return page to edit post
     *
     * @param Post $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Post $post)
    {
        if(Auth::guest() || !Auth::user()->can('update', $post)){
            throw new AccessDeniedHttpException("Could not update post - {$post->id}");
        }

        return view('post.create')->with(['post' => $post]);
    }

    /**
     * update post
     *
     * @param PostStoreRequest $request
     * @param Post $post
     * @param PostService $postService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PostStoreRequest $request, Post $post, PostService $postService)
    {
        if(Auth::guest() || !Auth::user()->can('update', $post)){
            throw new AccessDeniedHttpException("Could not update post - {$post->id}");
        }

        $post = $postService->update($post, $request);
        return redirect()->route('post.show', $post);

    }

    /**
     * return page to show post
     *
     * @param Request $request
     * @param Post $post
     * @return $this
     */
    public function show(Request $request, Post $post)
    {
        return view('post.show')->with([
            'post' => $post
        ]);
    }
}
