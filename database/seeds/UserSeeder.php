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
        $users = User::factory()->times(10)->make();

        // 讓隱藏字段可見，並將數據集合轉換為數組
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到資料庫中
        User::insert($user_array);

        // 單獨處理第一個會員的數據
        $user = User::find(1);
        $user->name = 'Allen';
        $user->email = 'allen@email.com';
        $user->save();
    }
}
