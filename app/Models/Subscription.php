<?php

namespace App\Models;

use App\Interfaces\Validatable;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model implements Validatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'target_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }

    /**
     * return validation rules
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'exists:users,id',
            'target_id' => 'exists:users,id',
        ];
    }
}
