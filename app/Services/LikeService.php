<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace App\Services;

use App\Notifications;
use App\Models\Like;
use App\Repositories\LikeRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Notification;

class LikeService
{
    private $likeRepository;
    private $userRepository;
    private $postRepository;

    /**
     * UserService constructor.
     * @param LikeRepository $likeRepository
     * @param UserRepository $userRepository
     * @param PostRepository $postRepository
     */
    public function __construct(LikeRepository $likeRepository, UserRepository $userRepository, PostRepository $postRepository)
    {
        $this->likeRepository = $likeRepository;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
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
        if ($like && $like->like->getStatus() == $like_value){

            $this->likeRepository->delete($like->id);
            return null;

        } else {

            $like = ($like)?$like:null;
            $likeInstance = $this->likeRepository->store([
                'user_id' => $user_id,
                'post_id' => $post_id,
                'like' => $like_value,
            ], $like);

            $user = $this->userRepository->getById($user_id);
            $post = $this->postRepository->getById($post_id);
            $target = $post->author;

            if ($user->id != $target->id){
                if ($like_value){
                    Notification::send($target, new Notifications\UserLiked($post, $user));
                } else {
                    Notification::send($target, new Notifications\UserDisliked($post, $user));
                }
            }

            return $likeInstance;
        }
    }
}