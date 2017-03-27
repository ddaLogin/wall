<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class PostController extends Controller
{
    /**
     * return page to create new post
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        if(Auth::guest() || !Auth::user()->can('create', new Post())){
            throw new AccessDeniedException("Could not create post");
        }

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
        if(Auth::guest() || !Auth::user()->can('create', new Post())){
            throw new AccessDeniedException("Could not create post");
        }

        $post = $postService->publish($request);
        return redirect()->route('user.wall', ['nickname' => Auth::user()->nickname]);
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
            throw new AccessDeniedException("Could not update post - {$post->id}");
        }

        return view('post.create')->with(['post' => $post]);
    }

    public function update(PostStoreRequest $request, Post $post, PostService $postService)
    {
        if(Auth::guest() || !Auth::user()->can('update', $post)){
            throw new AccessDeniedException("Could not update post - {$post->id}");
        }

        $post = $postService->update($post, $request);
        return redirect()->route('user.wall', ['nickname' => Auth::user()->nickname]);
    }
}
