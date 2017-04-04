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
        'text',
    ];

    protected $guarded = [
        'author_id'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['link'];

    /**
     * get url to user wall
     *
     * @return bool
     */
    public function getLinkAttribute()
    {
        return $this->attributes['link'] = '#';
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
     * return Like instance if user like it
     * or return null
     *
     * @param $user_id
     * @return mixed|null
     */
    public function likeByUser($user_id)
    {
        $likeRepository = new LikeRepository();
        $like = $likeRepository->getByUserAndPost($user_id, $this->id);
        if ($like){
            return $like->like;
        } else return null;
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
        ];
    }

    /**
     * set new attribute "search_text" to show search text with context
     *
     * @param $q
     * @return mixed
     */
    public function setSearchText($q)
    {
        $context = $this->text;
        if (preg_match('/(?:\S+\s|){8}\S*'.$q.'\S*(?:\s\S+|){8}/iu', $context, $matches)){
            return $this->attributes['search_text'] = $matches[0];
        } else {
            return $this->attributes['search_text'] = mb_substr($context, 0, 80);
        }
    }
}
