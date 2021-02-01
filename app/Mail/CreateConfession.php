<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateConfession extends Mailable
{
    use Queueable, SerializesModels;
    public $createConfessionLinks, $name;

    /**
     * Create a new message instance.
     *
     * @param array $createConfessionLinks
     * @param string $name
     */
    public function __construct(array $createConfessionLinks, string $name)
    {
        $this->createConfessionLinks = $createConfessionLinks;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): CreateConfession
    {
        return $this->markdown('emails.createConfession');
    }
}
