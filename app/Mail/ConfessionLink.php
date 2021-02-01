<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfessionLink extends Mailable
{
    use Queueable, SerializesModels;
    public $confessionLinks, $name;

    /**
     * Create a new message instance.
     *
     * @param array $confessionLink
     * @param string $name
     */
    public function __construct(array $confessionLink, string $name)
    {
        $this->confessionLinks = $confessionLink;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): ConfessionLink
    {
        return $this->markdown('emails.confessionLink');
    }
}
