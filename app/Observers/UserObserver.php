<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    // 將連續二個換行或以上，換成一個
    public function saving(User $user)
    {
        $user->introduction = preg_replace('/(\\r\\n|\\r|\\n){2,}/u', PHP_EOL, $user->introduction);
    }
}
