<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedCategoriesData extends Migration
{
    public function up()
    {
        $categories = [
            [
                'name' => '日常分享',
                'icon' => 'bi bi-chat-dots-fill',
                'description' => '想聊啥就聊啥',
            ],
            [
                'name' => '程式技術',
                'icon' => 'bi bi-code-slash',
                'description' => '程式技術交流與分享',
            ],
            [
                'name' => '電玩遊戲',
                'icon' => 'bi bi-controller',
                'description' => '電玩遊戲話題與心得',
            ],
        ];

        DB::table('categories')->insert($categories);
    }

    public function down()
    {
        DB::table('categories')->truncate();
    }
}
