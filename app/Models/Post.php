<?php

namespace App\Models;

use App\Interfaces\Validatable;
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

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
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
}
