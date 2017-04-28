<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:36
 */

namespace App\Interfaces;


use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostRepository
{
    /**
     * return post by id
     *
     * @param $id
     * @return Post
     */
    public function getById($id);

    /**
     * store new post
     *
     * @param $data
     * @param $userId
     * @return Post
     */
    public function store($data, $userId);

    /**
     * return posts collections, by author id
     *
     * @param $authorId
     * @return Collection
     */
    public function getByAuthorId($authorId);

    /**
     * search posts by text
     *
     * @param $q
     * @return Collection
     */
    public function search($q);

    /**
     * get top posts for home page
     *
     * @return Collection
     */
    public function getTopPosts();

    /**
     * return all user's tags
     *
     * @param $user_id
     * @return array
     */
    public function getTagsByUser($user_id);
}