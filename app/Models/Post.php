<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, Traits\SerializeDate;

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

    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的數據讀取邏輯
        switch ($order) {
            case 'recent':
                $query->recentReplied();
                break;

            default:
                $query->latest();
                break;
        }
    }

    public function scopeRecentReplied($query)
    {
        // 當話題有新回覆時，我們將編寫邏輯來更新話題模型的 reply_count 屬性，
        // 此時會自動觸发框架對數據模型 updated_at 時間戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    // 將連結加上 slug
    // $params 設定為 array，是為了方便 merge
    public function link($params = [])
    {
        return route('posts.show', array_merge([$this->id, $this->slug], $params));
    }

    // 更新回覆數量
    public function updateReplyCount()
    {
        $this->reply_count = $this->replies->count();
        $this->save();
    }
}
