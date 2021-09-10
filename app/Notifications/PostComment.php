<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Comment;

class PostComment extends Notification
{
    use Queueable;

    protected $comment;

    public function __construct(Comment $comment)
    {
        // 注入留言實體，方便 toDatabase 方法中的使用
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        // 開啟通知的頻道
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $post = $this->comment->post;
        $link = $post->link_with_slug . '#post-' . $post->id . '-comments';

        // 存入資料庫裡的數據
        return [
            'comment_id' => $this->comment->id,
            'comment_content' => $this->comment->content,
            'user_id' => $this->comment->user->id,
            'user_name' => $this->comment->user->name,
            'user_avatar' => $this->comment->user->gravatar(),
            'post_link' => $link,
            'post_id' => $post->id,
            'post_title' => $post->title,
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

}
