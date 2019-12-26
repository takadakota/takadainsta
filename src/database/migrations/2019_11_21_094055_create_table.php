<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTable extends Migration
{
        /**
     * マイグレーション実行
     *
     * @return void
     */
    public function up(){

    //メモ：元々ログイン課題のuserテーブルだったが、マイグレートし直しでそのまま流用（とりあえず動く→裏の設定気にしなくてよさそうだから）
    //12/11,19:19DB直接からモデル経由に手術
        Schema::create('user', function (Blueprint $table) {
          $table->bigIncrements('user_id');
          $table->string('github_id');//後のusername
          $table->string('ico')->nullable();//画像パスになりそう？githubから持ってきかたわからん、とりあえずnullでもOKの設定
          $table->timestamps();
        });
    }

    /**
     * マイグレーションを元に戻す
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user');
    }
}
