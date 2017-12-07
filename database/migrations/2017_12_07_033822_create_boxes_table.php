<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voice')->nullable()->comment('语音');
            $table->json('image')->nullable()->comment('图片');
            $table->text('body')->nullable()->comment('文字');
            $table->json('video')->nullable()->comment('视频');
            $table->string('qrcode')->nullable()->comment('二维码');
            $table->tinyInteger('status')->default(1)->comment('1:待使用2:已使用');
            $table->integer('user_id')->unsigned()->nullable()->comment('分类Id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('boxes');
    }
}
