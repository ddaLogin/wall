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
     * store new user
     *
     * @param $data
     * @return User
     */
    public function store($data)
    {
        $user = new User($data);
        $user->password = bcrypt($user->password);
        $user->save();

        return $user;
    }

    /**
     * update user photo
     *
     * @param $user_id
     * @param $url
     * @param $urlMini
     * @return mixed
     */
    public function updatePhoto($user_id, $url, $urlMini)
    {
        $user = $this->getById($user_id);
        if($user->photo != null){
            Storage::delete($user->photo);
        }

        if($user->photo_mini != null){
            Storage::delete($user->photo_mini);
        }

        $user->photo = $url;
        $user->photo_mini = $urlMini;
        $user->save();

        return true;
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
     * @return Collection
     */
    public function search($q)
    {
        $language = config('values.fullTextSearchLanguage');
        $q = str_replace(' ', ' & ', $q);
        $users = User::from(
            DB::raw("
                (SELECT users.*, 
                    ts_headline('{$language}', nickname,q,'StartSel=<searched-word>,StopSel=</searched-word>,MaxWords=50,MinWords=10') as searched_nickname, 
                    ts_headline('{$language}', email,q,'StartSel=<searched-word>,StopSel=</searched-word>') as searched_email, 
                    ts_rank_cd(searchable, q) as rank 
                    FROM users, to_tsquery('{$language}', '{$q}') as q 
                    WHERE searchable @@ q
                ) as search
            ")
        )->orderBy('search.rank', 'desc')
            ->take(7)
            ->get();
        return $users;
    }
}