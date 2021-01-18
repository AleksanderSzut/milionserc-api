<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfessionCreated extends Mailable
{
    use Queueable, SerializesModels;
    public $confessionLink, $name;

    /**
     * Create a new message instance.
     *
     * @param string $confessionLink
     * @param string $name
     */
    public function __construct(string $confessionLink, string $name)
    {
        $this->confessionLink = $confessionLink;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.confessionCreated');
    }
}
