<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function updating(User $user)
    {
        // 替換連續兩次以上空白與換行的混合
        $user->introduction = preg_replace('/(\s*(\\r\\n|\\r|\\n)\s*){2,}/u', PHP_EOL, $user->introduction);
    }
}
