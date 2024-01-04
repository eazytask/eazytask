<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyDeleteShift extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user,$roster,$ext;

    public function __construct($user,$roster,$ext='deleted')
    {
        $this->user= $user;
        $this->roster= $roster;
        $this->ext= $ext;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Shift '.ucwords($this->ext))
        ->markdown('emails.delete-shift-notify');
    }
}
