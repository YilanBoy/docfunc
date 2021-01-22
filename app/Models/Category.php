<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Traits\SerializeDate;

    public $timestamps = false;

    protected $fillable = [
        'name', 'icon', 'description',
    ];

    // 將連結加上標籤名稱
    public function getLinkWithNameAttribute()
    {
        return route('categories.show', ['category' => $this->id, 'name' => $this->name]);
    }
}
