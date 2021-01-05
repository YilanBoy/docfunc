<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Verified;
use App\Listeners\EmailVerified;
use App\Models\User;
use App\Models\Post;
use App\Models\Reply;
use App\Observers\UserObserver;
use App\Observers\PostObserver;
use App\Observers\ReplyObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        Verified::class => [
            EmailVerified::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // 註冊 Observe
        User::observe(UserObserver::class);
        Post::observe(PostObserver::class);
        Reply::observe(ReplyObserver::class);
    }
}
