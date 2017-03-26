<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace App\Services;


use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostService
{
    /**
     * store post
     *
     * @param Request $request
     * @return Post
     */
    public function store(Request $request)
    {
        $post = new Post($request->all());
        $post->author_id = Auth::user()->id;
        $post->save();

        return $post;
    }
}