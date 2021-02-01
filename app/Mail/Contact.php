<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;
    public  $name, $email, $content;

    /**
     * Create a new message instance.
     *
     * @param string $email
     * @param string $name
     * @param string $content
     */
    public function __construct(string $email,string $name,string  $content)
    {
        $this->email = $email;
        $this->name = $name;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): Contact
    {
        return $this->markdown('emails.contact');
    }
}
