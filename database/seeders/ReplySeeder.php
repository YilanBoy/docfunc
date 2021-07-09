<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reply;

class ReplySeeder extends Seeder
{
    public function run()
    {
        Reply::factory()->count(1000)->create();
    }
}
