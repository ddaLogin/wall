<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace App\Services;


use App\Models\Like;
use App\Repositories\LikeRepository;

class LikeService
{
    private $likeRepository;

    /**
     * UserService constructor.
     * @param LikeRepository $likeRepository
     */
    public function __construct(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    /**
     * toggle like
     * return null, when like removed
     * return Like instance when like set
     *
     * @param $user_id
     * @param $post_id
     * @param $like_value
     * @return Like|null
     */
    public function toggleLike($user_id, $post_id, $like_value)
    {
        $like = $this->likeRepository->getByUserAndPost($user_id, $post_id);
        if ($like && $like->like == $like_value){

            $this->likeRepository->delete($like->id);
            return null;

        } else {

            $like = ($like)?$like:null;
            return $this->likeRepository->store([
                'user_id' => $user_id,
                'post_id' => $post_id,
                'like' => $like_value,
            ], $like);
        }
    }
}