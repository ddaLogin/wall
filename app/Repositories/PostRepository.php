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
     * @return Collection
     */
    public function getByAuthorId($authorId)
    {
        return Post::where('author_id', $authorId)->orderBy('created_at', 'desc')->get();
    }

    /**
     * search posts by text
     *
     * @param string $q
     * @return Collection
     */
    public function search($q)
    {
        $language = config('values.fullTextSearchLanguage');
        $q = str_replace(' ', ' & ', $q);
        $posts = Post::from(
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
            ->orderBy('search.rank', 'desc')
            ->take(7)
            ->get();
        return $posts;
    }

    /**
     * get top posts for home page
     *
     * @return Collection
     */
    public function getTopPosts()
    {
        return Post::orderBy('created_at', 'desc')->take(10)->get();
    }

    /**
     * returns all the users posts
     *
     * @param array $usersIdArray
     * @return Collection
     */
    public function getByUsers($usersIdArray)
    {
        return Post::whereIn('author_id', $usersIdArray)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
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
}