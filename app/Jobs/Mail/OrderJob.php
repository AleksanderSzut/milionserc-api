<?php

namespace App\Jobs\Mail;

use App\Mail\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class OrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $paymentLink, $fullName, $toPay, $email;

    /**
     * Create a new job instance.
     *
     * @param $paymentLink
     * @param $fullName
     * @param $toPay
     */
    public function __construct($email, $paymentLink, $fullName, $toPay)
    {
        $this->email =  $email;
        $this->paymentLink = $paymentLink;
        $this->fullName = $fullName;
        $this->toPay = $toPay;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new Order($this->paymentLink, $this->fullName, $this->toPay);
        Mail::to($this->email)->send($email);
    }
}
