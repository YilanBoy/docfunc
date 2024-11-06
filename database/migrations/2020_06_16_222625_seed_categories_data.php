<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categories = [
            [
                'name' => '日常分享',
                'icon' => 'bi bi-chat-dots-fill',
                'description' => '想聊啥就聊啥',
            ],
            [
                'name' => '程式技術',
                'icon' => 'bi bi-terminal-fill',
                'description' => '程式技術交流與分享',
            ],
            [
                'name' => '電玩遊戲',
                'icon' => 'bi bi-dpad-fill',
                'description' => '電玩遊戲話題與心得',
            ],
        ];

        DB::table('categories')->insert($categories);
    }

    public function down(): void
    {
        DB::table('categories')->truncate();
    }
};
