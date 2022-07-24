<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    public static function isRegisterAllowed(): bool
    {
        $allowRegister = Setting::query()
            ->where('key', 'allow_register')
            ->pluck('value')
            ->first();

        return filter_var($allowRegister, FILTER_VALIDATE_BOOLEAN);
    }
}
