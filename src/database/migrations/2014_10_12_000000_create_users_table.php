<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');//デフォルト、使う
            $table->string('name')->nullable();//デフォルト、使わない
            $table->string('email')->unique()->nullable();//デフォルト、使わない
            $table->timestamp('email_verified_at')->nullable();//デフォルト、使わない
            $table->string('password')->nullable();//デフォルト、使わない
            $table->rememberToken()->nullable();//デフォルト、使わない
            $table->timestamps();//デフォルト、使わない
            $table->string('username')->nullable();//github_id
            $table->string('avatar')->nullable();//avatarで持ってくるプロフ画像、pathデータ？
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
