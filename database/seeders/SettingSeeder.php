<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->create([
            'name' => '開放註冊',
            'key' => 'allow_register',
            'value' => 'false',
        ]);
    }
}
