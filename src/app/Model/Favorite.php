<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $primaryKey = 'favorite_id';//これがないと主キーが変更できないらしい
    protected $fillable = ['user_id', 'tweet_id','favorite'];
    /**
     * リレーション
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    /**
     * リレーション
     */
    public function Tweet()
    {
        return $this->belongsTo('App\Model\Tweet');
    }
}
