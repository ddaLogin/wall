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
     * @param int $limit
     * @return Collection
     */
    public function getByAuthorId($authorId, $limit = null);

    /**
     * get top posts for home page
     *
     * @param int $limit
     * @return Collection
     */
    public function getTopPosts($limit = null);

    /**
     * returns all the users posts
     *
     * @param array $usersIdArray
     * @param null $limit
     * @return Collection
     */
    public function getByUsers($usersIdArray, $limit = null);

    /**
     * return all user's tags
     *
     * @param $user_id
     * @param int $limit
     * @return array
     */
    public function getTagsByUser($user_id, $limit = null);
}