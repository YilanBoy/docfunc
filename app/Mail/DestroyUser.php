<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DestroyUser extends Mailable
{
    use Queueable, SerializesModels;

    public $destroyLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $destroyLink)
    {
        $this->destroyLink = $destroyLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('刪除帳號確認')->view('emails.destroy-user');
    }
}
