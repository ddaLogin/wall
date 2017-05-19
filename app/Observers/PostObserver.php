<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 13.04.2017
 * Time: 14:09
 */

namespace App\Observers;


use App\Interfaces\PostRepository;
use App\Models\Post;

class PostObserver
{
    private $postRepository;
    /**
     * PostObserver constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function created(Post $post)
    {
        $this->postRepository->updateFullTextSearchField($post->id);
    }

    public function updated(Post $post)
    {
        $this->postRepository->updateFullTextSearchField($post->id);
    }
}