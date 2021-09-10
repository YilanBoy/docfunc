<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['post_id']);
        });

        Schema::drop('comments');
    }
}
