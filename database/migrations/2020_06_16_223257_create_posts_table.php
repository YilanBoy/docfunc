<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->mediumText('body');
            $table->integer('category_id')->unsigned()->index();
            $table->integer('comment_count')->unsigned()->default(0);
            $table->text('excerpt')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // 上面這句等於以下兩句
            // $table->bigInteger('user_id')->unsigned()->index();
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // 移除外鍵約束
            $table->dropForeign(['user_id']);
        });

        Schema::drop('posts');
    }
}
