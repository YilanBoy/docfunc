<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index()->comment('名稱');
            $table->string('icon')->nullable()->comment('圖示');
            $table->text('description')->nullable()->comment('描述');
            $table->integer('post_count')->default(0)->comment('文章數');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
