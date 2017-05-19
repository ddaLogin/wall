<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 26.03.2017
 * Time: 20:50
 */

namespace App\Repositories;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PgSqlLikeRepository implements \App\Interfaces\LikeRepository
{

    /**
     * return like by user id and post id
     *
     * @param $user_id
     * @param $post_id
     * @return Like
     */
    public function getByUserAndPost($user_id, $post_id)
    {
        return Like::where('user_id', $user_id)
            ->where('post_id', $post_id)
            ->first();
    }

    /**
     * store new like or dislike
     *
     * @param $data
     * @param Like|null $like
     * @return mixed
     */
    public function store($data, Like $like = null)
    {
        if (!$like){
            $like = new Like();
        }

        $like->user_id = $data['user_id'];
        $like->post_id = $data['post_id'];
        $like->like = $data['like'];
        $like->save();

        return $like;
    }

    /**
     * delete like by id
     *
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        Like::where('id', $id)->delete();

        return true;
    }

    /**
     * return all likes by post id
     *
     * @param $post_id
     * @return Collection
     */
    public function getLikesByPost($post_id)
    {
        return Like::where('like', true)
            ->where('post_id', $post_id)
            ->get();
    }

    /**
     * return all dislikes by post id
     *
     * @param $post_id
     * @return Collection
     */
    public function getDislikesByPost($post_id)
    {
        return Like::where('like', false)
            ->where('post_id', $post_id)
            ->get();
    }
}