<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
  protected $fillable = ['filename']; //テーブルで使う要素名の中で画面に表示させたいもの
}
