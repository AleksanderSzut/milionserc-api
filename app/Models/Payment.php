<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Mixed_;

class Payment extends Model
{
    const TPAY_URL = "https://secure.tpay.com";
    use HasFactory;


    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function getTransactionLink(): String
    {
        $to_pay = $this->to_pay / 100;
        $description = "Wyznania milionserc";
        $order = $this->order;
        $orderId = $order->id;
        $email = $order->billing->email;
        $name = $order->billing->full_name;
        $resultUrl = config('app.url') . '?transaction_confirmation';
        $returnUrl = env('APP_API_URL') . '/api/transactionReturnUrl';

        $transactionApi = new TransactionApi();

        return $transactionApi->createTransaction($to_pay, $description, $email, $name, $orderId, $resultUrl, $returnUrl);
    }
}
