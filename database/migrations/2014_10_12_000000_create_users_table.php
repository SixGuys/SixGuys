<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('phone')->nullable();
            $table->string('name')->nullable();
            $table->string('third_party')->nullable()->comment('第三方登录open_id');
            $table->tinyInteger('third_type')->nullable()->comment('第三方平台标识 1 微信，2 QQ，3，微博');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('auther')->default(0)->comment('是否是作者');
            $table->string('activation_token')->nullable()->comment('邮箱激活token');
            $table->boolean('activated')->default(false)->comment('是否激活');
            $table->integer('status')->default(1)->comment('用户状态 0 禁用，1 正常');
            $table->rememberToken();
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
