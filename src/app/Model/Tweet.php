<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $primaryKey = 'tweet_id';//これがないと主キーが変更できないらしい
    protected $fillable = ['user_id','username','imagepath','caption']; //12/10username追加
    /**
     * リレーション１２２３
     */
     public function favorite()
     {
         return $this->hasMany('App\Model\Favorite');//第２引数にtweet_idは試した12/26
     }
}
