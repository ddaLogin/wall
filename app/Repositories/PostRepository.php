<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 26.03.2017
 * Time: 20:50
 */

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PostRepository implements \App\Interfaces\PostRepository
{
    /**
     * return post by id
     *
     * @param $id
     * @return Post
     */
    public function getById($id)
    {
        return Post::findorfail($id);
    }

    /**
     * store new post
     *
     * @param $data
     * @param $userId
     * @param Post $post
     * @return Post
     */
    public function store($data, $userId, Post $post = null)
    {
        if (!$post){
            $post = new Post();
        }

        foreach ($data['tags'] as $key=>$tag){
            if (starts_with($tag, '#')) $data['tags'][$key] = ltrim($tag, '#');
        }

        $post->fill($data);
        $post->author_id = $userId;
        $post->save();

        return $post;
    }

    /**
     * return posts collections, by author id
     *
     * @param $authorId
     * @param int $limit
     * @return Collection
     */
    public function getByAuthorId($authorId, $limit = null)
    {
        $query = Post::where('author_id', $authorId)->orderBy('created_at', 'desc');

        if ($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    /**
     * search posts by text
     *
     * @param string $q
     * @param int $limit
     * @return Collection
     */
    public function search($q, $limit = null)
    {
        $language = config('values.fullTextSearchLanguage');
        $q = str_replace(' ', ' & ', $q);
        $query = Post::from(
            DB::raw("
                (SELECT posts.*, 
                    ts_headline('{$language}', text,q,'StartSel=<searched-word>,StopSel=</searched-word>,MaxWords=50,MinWords=10') as searched_text, 
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
     * get top posts for home page
     *
     *@param int $limit
     * @return Collection
     */
    public function getTopPosts($limit = null)
    {
        $query = Post::orderBy('created_at', 'desc');

        if ($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    /**
     * returns all the users posts
     *
     * @param array $usersIdArray
     * @param null $limit
     * @return Collection
     */
    public function getByUsers($usersIdArray, $limit = null)
    {
        $query = Post::whereIn('author_id', $usersIdArray)
            ->orderBy('created_at', 'desc');

        if($limit){
            return $query->paginate($limit);
        }

        return $query->get();
    }

    /**
     * Update search field for Postgres Full Text Search system
     *
     * @param $post_id
     */
    public function updateFullTextSearchField($post_id)
    {
        $language = config('values.fullTextSearchLanguage');
        DB::statement("UPDATE posts SET searchable = setweight(to_tsvector('{$language}', tags::text), 'B') ||' '|| setweight(to_tsvector('{$language}', text), 'D') WHERE id = {$post_id}");
    }

    /**
     * return all user's tags
     *
     * @param $user_id
     * @param int $limit
     * @return array
     */
    public function getTagsByUser($user_id, $limit = null)
    {
        $query = DB::table('posts')
                ->select(DB::raw('jsonb_array_elements_text(tags) as tag, COUNT(*) as cnt'))
                ->from('posts')
                ->where('posts.author_id', $user_id)
                ->groupBy('tag')
                ->orderBy('cnt', 'desc');

        if ($limit) {
            $query = $query->take($limit);
        }

        return $query->get();
    }
}