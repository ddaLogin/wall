<?php

namespace App\Models;

use App\Interfaces\LikeRepository;
use App\Interfaces\Validatable;
use App\Repositories\PgSqlLikeRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Post extends Model implements Validatable
{
    private $likeRepository;

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
    protected $appends = ['link'];

    /**
     * Post constructor.
     * @param array $attributes
     */
    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->likeRepository = App::make(LikeRepository::class);
    }

    /**
     * get url to post view page
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
     * return like status as class by user_id
     *
     * @param $user_id
     * @return \App\Classes\Like
     */
    public function likeStatusByUser($user_id)
    {
        $like = $this->likeRepository->getByUserAndPost($user_id, $this->id);
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

    /**
     * return first n words from text, don't mind html tags
     *
     * @param $text
     * @param $count
     * @param string $appends
     * @return mixed|string
     */
    public function cutByWords($text, $count, $appends = ' ...')
    {
        $count++;
        $clearText = preg_replace("|[\s]+|is", " ", $text);
        $words = explode(' ', $clearText, $count);
        if (count($words) >= $count) {
            array_pop($words);
        } else {
            $appends = '';
        }

        $clearText = implode(' ', $words);

        return $clearText.$appends;
    }
}
