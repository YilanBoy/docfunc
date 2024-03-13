<?php

namespace App\Models;

use App\Notifications\NewComment;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property DatabaseNotificationCollection $unreadNotifications
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'introduction',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function isAuthorOf(Post|Comment $model): bool
    {
        return $this->id === $model->user_id;
    }

    public function notifyNewComment(NewComment $instance): void
    {
        // if the author of the comment is the same as the author of the post, don't notify
        if ($this->id === auth()->id()) {
            return;
        }

        $this->notify($instance);
    }

    public function markAsRead(): void
    {
        $this->unreadNotifications->markAsRead();
    }

    public function gravatarUrl(): Attribute
    {
        $hash = md5(strtolower(trim($this->email)));

        return new Attribute(
            get: fn ($value) => 'https://www.gravatar.com/avatar/'.$hash.'?s=400&d=mp'
        );
    }
}
