<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['value'];

    protected $casts = ['value' => 'json'];
}
