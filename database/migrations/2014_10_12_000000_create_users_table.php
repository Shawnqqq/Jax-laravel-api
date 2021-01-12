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
            $table->bigIncrements('id');
            $table->string('email')->nullable()->unique();
            $table->string('unionid')->nullable()->unique()->comment('微信 unionid');
            $table->string('phone')->nullable()->unique()->comment('电话');
            $table->string('password')->nullable()->comment('密码');
            $table->string('session_key')->nullable()->comment('微信小程序登录态');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('realname')->nullable()->comment('真实姓名');
            $table->unsignedTinyInteger('gender')->nullable()->comment('性别');
            $table->string('avatar_url')->nullable()->comment('用户图像url');
            $table->string('country')->nullable()->comment('国家');
            $table->string('province')->nullable()->comment('省');
            $table->string('city')->nullable()->comment('城市');
            $table->string('district')->nullable()->comment('区');
            $table->date('birthday')->nullable()->comment('出生日期');
            $table->string('introduction',500)->nullable()->comment('自我介绍');
            $table->datetime('visited_at')->nullable()->comment('最后登录时间');
            $table->timestamps();
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
