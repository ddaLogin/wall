<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 13.04.2017
 * Time: 14:09
 */

namespace App\Observers;

use App\Interfaces\SearchRepository;
use App\Models\Post;

class PostObserver
{
    private $searchRepository;

    /**
     * PostObserver constructor.
     * @param SearchRepository $searchRepository
     */
    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function created(Post $post)
    {
        $this->searchRepository->updateFullTextSearchPostsField($post->id);
    }

    public function updated(Post $post)
    {
        $this->searchRepository->updateFullTextSearchPostsField($post->id);
    }
}