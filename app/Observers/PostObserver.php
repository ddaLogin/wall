<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 13.04.2017
 * Time: 14:09
 */

namespace App\Observers;


use App\Models\Post;
use App\Repositories\PostRepository;

class PostObserver
{
    public function created(Post $post)
    {
        (new PostRepository())->updateFullTextSearchField($post->id);
    }

    public function updated(Post $post)
    {
        (new PostRepository())->updateFullTextSearchField($post->id);
    }
}