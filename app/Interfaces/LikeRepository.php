<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:36
 */

namespace App\Interfaces;


use App\Models\Like;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

interface LikeRepository
{
    /**
     * return like by user id and post id
     *
     * @param $user_id
     * @param $post_id
     * @return Like
     */
    public function getByUserAndPost($user_id, $post_id);

    /**
     * store new like or dislike
     *
     * @param $data
     * @param Like|null $like
     * @return mixed
     */
    public function store($data, Like $like = null);

    /**
     * delete like by id
     *
     * @param $id
     * @return boolean
     */
    public function delete($id);

    /**
     * return all likes by post id
     *
     * @param $post_id
     * @return Collection
     */
    public function getLikesByPost($post_id);

    /**
     * return all dislikes by post id
     *
     * @param $post_id
     * @return Collection
     */
    public function getDislikesByPost($post_id);
}