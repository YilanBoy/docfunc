<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'icon', 'description',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // 將連結加上分類名稱
    public function linkWithName(): Attribute
    {
        return new Attribute(
            get: fn ($value) => route('categories.show', [
                'category' => $this->id,
                'name' => $this->name,
            ])
        );
    }
}
