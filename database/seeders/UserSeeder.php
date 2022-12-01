<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 單獨處理第一個會員的數據
        $user = User::find(1);
        $user->update([
            'name' => 'Allen',
            'email' => 'allen@email.com',
        ]);

        Post::factory()->count(100)->userId($user->id)->create();
    }
}
