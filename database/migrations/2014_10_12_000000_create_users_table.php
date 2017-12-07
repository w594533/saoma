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
            $table->string('name')->nullable()->default('');
            $table->string('avatar')->nullable()->default('')->comment('头像');
            $table->tinyInteger('gender')->nullable()->default('0')->comment('性别1:男2:女');
            $table->string('email')->nullable()->default('')->comment('邮箱');
            $table->string('password')->nullable()->default('');
            $table->string('openid')->nullable()->default('')->comment('微信openid');
            $table->tinyInteger('status')->default('1');
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
