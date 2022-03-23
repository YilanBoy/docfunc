<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory, MassPrunable;

    public function prunable()
    {
        return static::whereNotNull('read_at')
            ->where('read_at', '<=', now()->subWeek());
    }
}
