<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reply;

class PostReplied extends Notification
{
    use Queueable;

    public $reply;

    public function __construct(Reply $reply)
    {
        // 注入回覆實體，方便 toDatabase 方法中的使用
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        // 開啟通知的頻道
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $post = $this->reply->post;
        $link = $post->link(['#reply' . $this->reply->id]);

        // 存入資料庫裡的數據
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->gravatar(),
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
