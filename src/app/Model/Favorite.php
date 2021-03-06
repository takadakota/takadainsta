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
    public function getData()
    {
        return $this->favorite;
    }
    /**
     * リレーション
     */
    public function Tweet()
    {
        return $this->belongsTo('App\Model\Tweet');
    }
}
