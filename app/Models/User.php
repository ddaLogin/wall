<?php

namespace App\Models;

use App\Interfaces\Validatable;
use App\Repositories\SubscriptionRepository;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements Validatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'email'
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
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'password', 'photo', 'photo_mini'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['link', 'photo_link', 'photo_link_mini', 'status'];

    /**
     * ganarate url to user wall
     *
     * @return bool
     */
    public function getLinkAttribute()
    {
        return $this->attributes['link'] = route('user.wall', $this->nickname);
    }

    /**
     * generate url to user photo
     *
     * @return bool
     */
    public function getPhotoLinkAttribute()
    {
        return $this->attributes['photo_link'] = ($this->photo)?Storage::disk('public')->url($this->photo):config('values.noPhoto');
    }

    /**
     * generate url to user photo
     *
     * @return bool
     */
    public function getPhotoLinkMiniAttribute()
    {
        return $this->attributes['photo_link_mini'] = ($this->photo)?Storage::disk('public')->url($this->photo_mini):config('values.noPhotoMini');
    }

    /**
     * generate url to user photo
     *
     * @return bool
     */
    public function getStatusAttribute()
    {
        return $this->attributes['status'] = Cache::get('User::'.$this->id.'::status', false);
    }

    /**
     * Set the password
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
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
