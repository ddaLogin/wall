<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 26.03.2017
 * Time: 20:50
 */

namespace App\Repositories;

use App\Interfaces\PostRepository;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class  PgSqlPostRepository implements PostRepository
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

        foreach ($data['tags'] as $key=>$tag){
            if (starts_with($tag, '#')) $data['tags'][$key] = ltrim($tag, '#');
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
     * @param int $limit
     * @return Collection
     */
    public function getByAuthorId($authorId, $limit = null)
    {
        $query = Post::where('author_id', $authorId)->orderBy('created_at', 'desc');

        if ($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    /**
     * get top posts for home page
     *
     *@param int $limit
     * @return Collection
     */
    public function getTopPosts($limit = null)
    {
        $query = Post::orderBy('created_at', 'desc');

        if ($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    /**
     * returns all the users posts
     *
     * @param array $usersIdArray
     * @param null $limit
     * @return Collection
     */
    public function getByUsers($usersIdArray, $limit = null)
    {
        $query = Post::whereIn('author_id', $usersIdArray)
            ->orderBy('created_at', 'desc');

        if($limit){
            return $query->paginate($limit);
        }

        return $query->get();
    }

    /**
     * return all user's tags
     *
     * @param $user_id
     * @param int $limit
     * @return array
     */
    public function getTagsByUser($user_id, $limit = null)
    {
        $query = DB::table('posts')
                ->select(DB::raw('jsonb_array_elements_text(tags) as tag, COUNT(*) as cnt'))
                ->from('posts')
                ->where('posts.author_id', $user_id)
                ->groupBy('tag')
                ->orderBy('cnt', 'desc');

        if ($limit) {
            $query = $query->take($limit);
        }

        return $query->get();
    }
}