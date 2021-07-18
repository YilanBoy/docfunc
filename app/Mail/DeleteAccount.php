<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeleteAccount extends Mailable
{
    use Queueable, SerializesModels;

    public $deleteAccountLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $deleteAccountLink)
    {
        $this->deleteAccountLink = $deleteAccountLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.delete-account');
    }
}
