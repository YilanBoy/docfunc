<?php

namespace App\Models\Traits;

use DateTimeInterface;

// 因 Laravel 7 調整了時間格式會導致 seeding 出錯，這裡對 serializeDate() 進行覆寫
trait SerializeDate
{
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
