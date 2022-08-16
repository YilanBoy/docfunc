<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;

class EmailVerified
{
    public function handle(Verified $event)
    {
        // 會話裡閃存認證成功後的消息提醒
        session()->flash('success', '信箱認證成功 ^_^');
    }
}
