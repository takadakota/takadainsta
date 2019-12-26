<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
         * リレーション1223
         */
        public function favorites()
        {
            return $this->hasMany('App\Model\Favorite');
        }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'user_id';//これがないと主キーが変更できないらしい
    protected $fillable = [
        'name', 'email', 'password','username','avatar',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
