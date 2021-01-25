<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, Traits\SerializeDate, Searchable;

    protected $fillable = [
        'title', 'body', 'category_id', 'excerpt', 'slug',
    ];

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 定義與標籤的關聯
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function scopeWithOrder($query, ?string $order)
    {
        // 不同的排序，使用不同的數據讀取邏輯
        if ($order === 'recent') {
            $query->recentReplied();
        } else {
            $query->latest();
        }
    }

    public function scopeRecentReplied($query)
    {
        // 當話題有新回覆時，我們將編寫邏輯來更新話題模型的 reply_count 屬性，
        // 此時會自動觸发框架對數據模型 updated_at 時間戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    // 將連結加上 slug
    public function getLinkWithSlugAttribute()
    {
        return route('posts.show', ['post' => $this->id, 'slug' => $this->slug]);
    }

    // 更新回覆數量
    public function updateReplyCount(): void
    {
        $this->reply_count = $this->replies->count();
        $this->save();
    }

    // 設定 Algolia 匯入的 index 名稱
    public function searchableAs()
    {
        return config('scout.prefix');
    }

    // 調整匯入 Algolia 的 Model 資料
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Applies Scout Extended default transformations:
        $array = $this->transform($array);

        $array['author_name'] = $this->user->name;
        $array['url'] = $this->getLinkWithSlugAttribute();

        return $array;
    }
}
