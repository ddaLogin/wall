<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 26.03.2017
 * Time: 20:50
 */

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class PostRepository implements \App\Interfaces\PostRepository
{
    /**
     * return post by id
     *
     * @param $id
     * @return Post
     */
    public function getById($id)
    {
        return Post::findorfail($id);
    }

    /**
     * store new post
     *
     * @param $data
     * @param $userId
     * @param Post $post
     * @return Post
     */
    public function store($data, $userId, Post $post = null)
    {
        if (!$post){
            $post = new Post();
        }

        $post->fill($data);
        $post->author_id = $userId;
        $post->save();

        return $post;
    }

    /**
     * return posts collections, by author id
     *
     * @param $authorId
     * @return Collection
     */
    public function getByAuthorId($authorId)
    {
        return Post::where('author_id', $authorId)->orderBy('created_at', 'desc')->get();
    }
}