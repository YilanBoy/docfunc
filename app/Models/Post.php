<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\MassPrunable;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class Post extends Model implements Feedable
{
    use HasFactory, Searchable, SoftDeletes, MassPrunable;

    protected $fillable = [
        'title', 'body', 'category_id', 'excerpt', 'slug',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 定義與標籤的關聯
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    // 文章排序
    public function scopeWithOrder($query, ?string $order)
    {
        return $query->when($order, function ($query, $order) {
            return match ($order) {
                'recent' => $query->orderBy('updated_at', 'desc'),
                'comment' => $query->orderBy('comment_count', 'desc'),
                default => $query->latest(),
            };
        });
    }

    /**
     * Get the prunable model query.
     *
     * @return Builder
     */
    public function prunable(): Builder
    {
        return static::where('deleted_at', '<=', now()->subMonth());
    }

    // 將連結加上文章的 slug
    public function linkWithSlug(): Attribute
    {
        return new Attribute(
            get: fn($value) => route('posts.show', [
                'post' => $this->id,
                'slug' => $this->slug,
            ])
        );
    }

    public function tagsJson(): Attribute
    {
        // 生成包含 tag ID 與 tag name 的 json 字串
        // [{"id":"2","value":"C#"},{"id":"5","value":"Dart"}]
        return new Attribute(
            get: fn($value) => $this->tags
                ->map(fn($tag) => ['id' => $tag->id, 'value' => $tag->name])
                ->toJson()
        );
    }

    // 更新留言數量
    public function updateCommentCount(): void
    {
        // 使用鎖表來更新 Model 中的 comment_count 資料
        $this->comment_count = Comment::where('post_id', $this->id)->lockForUpdate()->count();
        // 更新資料庫資料
        $this->save();
    }

    // 設定 Algolia 匯入的 index 名稱
    public function searchableAs()
    {
        return config('scout.prefix');
    }

    // 調整匯入 Algolia 的 Model 資料
    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        // Applies Scout Extended default transformations:
        $array = $this->transform($array);

        $array['author_name'] = $this->user->name;
        $array['url'] = $this->link_with_slug;

        return $array;
    }

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->id)
            ->title($this->title)
            ->summary($this->excerpt)
            ->updated($this->updated_at)
            ->link($this->link_with_slug)
            ->authorName($this->user->name);
    }

    public static function getFeedItems(): Collection
    {
        return Post::latest()->take(10)->get();
    }
}
