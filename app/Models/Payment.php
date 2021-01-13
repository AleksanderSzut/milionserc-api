<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Mixed_;

class Payment extends Model
{
    use HasFactory;
    const STATUS_WAITING = 0;
    const STATUS_PAID = 1;
    const STATUS_REJECTED = 2;

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function createTransaction(): bool
    {
        $to_pay = $this->to_pay / 100;
        $description = "Wyznania milionserc";
        $order = $this->order;
        $orderId = $order->id;
        $email = $order->billing->email;
        $name = $order->billing->full_name;
        $returnUrl = config('app.url') . '?transaction_confirmation';
        $resultUrl = env('APP_API_URL') . '/api/transactionReturnUrl';

        $transactionApi = new TransactionApi();

        $result = $transactionApi->createTransaction($to_pay, $description, $email, $name, $orderId, $resultUrl, $returnUrl);

        if ($result['result']) {
            $this->transaction_title = $result['title'];
            $this->save();
            return true;
        } else
            return false;

    }

    public function getTransactionLink(): string
    {
        return "https://secure.tpay.com/?gtitle=" . $this->transaction_title;
    }
}
