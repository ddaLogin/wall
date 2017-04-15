<?php

namespace App\Models;

use App\Interfaces\Validatable;
use Illuminate\Database\Eloquent\Model;

class Like extends Model implements Validatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id', 'like'
    ];

    /**
     * return like value as class
     *
     * @param $value
     * @return \App\Classes\Like
     */
    public function getLikeAttribute($value)
    {
        return new \App\Classes\Like($value);
    }

    /**
     * return validation rules
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'like' => 'required|boolean',
        ];
    }
}
