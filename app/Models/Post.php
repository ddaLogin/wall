<?php

namespace App\Models;

use App\Interfaces\Validatable;
use App\Repositories\LikeRepository;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements Validatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text', 'tags'
    ];

    protected $guarded = [
        'author_id'
    ];

    protected $casts = [
        'tags' => 'json',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['link', 'hashtags'];

    /**
     * get url to post view page
     *
     * @return bool
     */
    public function getLinkAttribute()
    {
        return $this->attributes['link'] = '#';
    }

    /**
     * add '#' char to each tag
     *
     * @return bool
     */
    public function getHashtagsAttribute()
    {
        $hashtags = [];
        foreach ($this->tags as $key=>$tag){
            $hashtags[$key] = '#'.$tag;
        }

        return $this->attributes['hashtags'] = $hashtags;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id')->where('like', true);
    }

    public function dislikes()
    {
        return $this->hasMany(Like::class, 'post_id')->where('like', false);
    }

    /**
     * return like status as class by user_id
     *
     * @param $user_id
     * @return \App\Classes\Like
     */
    public function likeStatusByUser($user_id)
    {
        $likeRepository = new LikeRepository();
        $like = $likeRepository->getByUserAndPost($user_id, $this->id);
        if (isset($like)){
            return $like->like;
        } else return new \App\Classes\Like(null);
    }

    /**
     * return validation rules
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required|string',
            'author_id' => 'exists:users,id',
            'tags' => 'required|array',
        ];
    }
}
