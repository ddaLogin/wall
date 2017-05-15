<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 26.03.2017
 * Time: 20:50
 */

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserRepository implements \App\Interfaces\UserRepository
{
    /**
     * return user by id
     *
     * @param $id
     * @return User
     */
    public function getById($id)
    {
        return User::findorfail($id);
    }

    /**
     * return user by nickname
     *
     * @param $nickname
     * @return User
     */
    public function getByNickname($nickname)
    {
        return User::where('nickname', $nickname)->first();
    }

    /**
     * store user
     *
     * @param $data
     * @param null $user_id
     * @return User
     */
    public function store($data, $user_id = null)
    {
        $user = ($user_id)?$this->getById($user_id):new User($data);
        $user->fill($data);

        if (key_exists('password', $data)){
            $user->password = $data['password'];
        }

        if (key_exists('photo', $data)){
            $user->photo = $data['photo'];
        }

        if (key_exists('photo_mini', $data)){
            $user->photo_mini = $data['photo_mini'];
        }

        $user->save();

        return $user;
    }

    /**
     * Update search field for Postgres Full Text Search system
     *
     * @param $user_id
     */
    public function updateFullTextSearchField($user_id)
    {
        $language = config('values.fullTextSearchLanguage');
        DB::statement("UPDATE users SET searchable = setweight(to_tsvector('{$language}', nickname), 'B') ||' '|| setweight(to_tsvector('{$language}', email), 'D') WHERE id = {$user_id}");
    }

    /**
     * search users by nickname or mails
     *
     * @param $q
     * @param int $limit
     * @return Collection
     */
    public function search($q, $limit = null)
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
}