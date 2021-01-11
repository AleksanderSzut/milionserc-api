<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Order extends Mailable
{
    use Queueable, SerializesModels;
    public $paymentLink, $name, $toPay;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($paymentLink, $name, $toPay)
    {
        $this->paymentLink = $paymentLink;
        $this->name = $name;
        $this->toPay = $toPay;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.order');
    }
}
