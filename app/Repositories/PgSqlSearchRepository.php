<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 26.03.2017
 * Time: 20:50
 */

namespace App\Repositories;

use App\Interfaces\SearchRepository;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class  PgSqlSearchRepository implements SearchRepository
{
    /**
     * search posts by text
     *
     * @param $q
     * @param int $limit
     * @return Collection
     */
    public function searchPosts($q, $limit = null)
    {
        $language = config('values.fullTextSearchLanguage');
        $minWordsCount = config('values.postShortTextWordCount');
        $maxWordsCount = $minWordsCount + 2;
        $q = str_replace(' ', ' & ', $q);
        $query = Post::from(
            DB::raw("
                (SELECT posts.*, 
                    ts_headline('{$language}', text,q,'StartSel=<searched-word>,StopSel=</searched-word>,MaxWords={$maxWordsCount},MinWords={$minWordsCount}') as searched_text, 
                    ts_headline('{$language}', tags::text,q,'StartSel=<searched-word>,StopSel=</searched-word>') as searched_tags, 
                    ts_rank_cd(searchable, q) as rank 
                    FROM posts, to_tsquery('{$language}', '{$q}') as q 
                    WHERE searchable @@ q
                ) as search
            ")
        )->with('author')
            ->orderBy('search.rank', 'desc');

        if ($limit) {
            $query = $query->take($limit);
        }

        return $query->get();
    }

    /**
     * Update Full Text Search storage for posts
     *
     * @param $post_id
     */
    public function updateFullTextSearchPostsField($post_id)
    {
        $language = config('values.fullTextSearchLanguage');
        DB::statement("UPDATE posts SET searchable = setweight(to_tsvector('{$language}', tags::text), 'B') ||' '|| setweight(to_tsvector('{$language}', text), 'D') WHERE id = {$post_id}");
    }

    /**
     * search users by nickname or email
     *
     * @param $q
     * @param int $limit
     * @return Collection
     */
    public function searchUsers($q, $limit = null)
    {
        $language = config('values.fullTextSearchLanguage');
        $q = str_replace(' ', ' & ', $q);
        $query = User::from(
            DB::raw("
                (SELECT users.*, 
                    ts_headline('{$language}', nickname,q,'StartSel=<searched-word>,StopSel=</searched-word>,MaxWords=50,MinWords=10') as searched_nickname, 
                    ts_headline('{$language}', email,q,'StartSel=<searched-word>,StopSel=</searched-word>') as searched_email, 
                    ts_rank_cd(searchable, q) as rank 
                    FROM users, to_tsquery('{$language}', '{$q}') as q 
                    WHERE searchable @@ q
                ) as search
            ")
        )->orderBy('search.rank', 'desc');

        if ($limit){
            $query = $query->take($limit);
        }

        return $query->get();
    }

    /**
     * Update Full Text Search storage for users
     *
     * @param $user_id
     */
    public function updateFullTextSearchUsersField($user_id)
    {
        $language = config('values.fullTextSearchLanguage');
        DB::statement("UPDATE users SET searchable = setweight(to_tsvector('{$language}', nickname), 'B') ||' '|| setweight(to_tsvector('{$language}', email), 'D') WHERE id = {$user_id}");
    }
}