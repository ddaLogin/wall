<?php

namespace App\Models;

use App\Interfaces\Validatable;
use App\Repositories\SubscriptionRepository;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Validatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'email', 'password', 'photo', 'photo_mini'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
        return $this->attributes['link'] = route('user.wall', $this->nickname);
    }

    public function subscribers()
    {
        return $this->hasMany(Subscription::class, 'target_id');
    }

    /**
     * return true if user subscribe, else return false
     *
     * @param $user_id
     * @return bool
     */
    public function subscribeByUser($user_id)
    {
        $subscriptionRepository = new SubscriptionRepository();
        if ($subscriptionRepository->getByUserAndTarget($user_id, $this->id)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * return validation rules
     * @return array
     */
    public function rules()
    {
        return [
            'nickname' => 'required|min:4',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
