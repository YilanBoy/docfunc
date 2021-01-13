<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Traits\SerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'introduction',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function isAuthorOf($model): bool
    {
        return $this->id === $model->user_id;
    }

    public function postNotify($instance): void
    {
        // 如果要通知的人是當前用戶，就不必通知了！
        if ($this->id === auth()->user()->id) {
            return;
        }

        $this->increment('notification_count');
        $this->notify($instance);
    }

    public function markAsRead(): void
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function gravatar(string $size = '100'): string
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return 'https://www.gravatar.com/avatar/' . $hash . '?s=' . $size;
    }

}
