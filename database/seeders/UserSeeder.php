<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 生成數據集合
        $users = User::factory()->times(10)->create();

        // 單獨處理第一個會員的數據
        $user = User::find(1);
        $user->update([
            'name' => 'Allen',
            'email' => 'allen@email.com',
        ]);
    }
}
