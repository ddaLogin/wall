<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        return view('post.create');
    }

    public function store(PostStoreRequest $request, PostService $postService)
    {
        $post = $postService->store($request);
        return redirect()->route('home');
    }
}
