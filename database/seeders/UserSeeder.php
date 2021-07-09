<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 生成數據集合
        User::factory()->count(10)->create();

        // 單獨處理第一個會員的數據
        $user = User::find(1);
        $user->update([
            'name' => 'Allen',
            'email' => 'allen@email.com',
        ]);
    }
}
