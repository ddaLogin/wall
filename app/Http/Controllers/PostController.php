<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * return page to create new post
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('post.create');
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
        return redirect()->route('user.wall', ['nickname' => Auth::user()->nickname]);
    }
}
