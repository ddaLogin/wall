<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:36
 */

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface SearchRepository
{
    /**
     * search posts by text
     *
     * @param $q
     * @param int $limit
     * @return Collection
     */
    public function searchPosts($q, $limit = null);

    /**
     * Update Full Text Search storage for posts
     *
     * @param $post_id
     */
    public function updateFullTextSearchPostsField($post_id);

    /**
     * search users by nickname or email
     *
     * @param $q
     * @param int $limit
     * @return Collection
     */
    public function searchUsers($q, $limit = null);

    /**
     * Update Full Text Search storage for users
     *
     * @param $post_id
     */
    public function updateFullTextSearchUsersField($post_id);
}