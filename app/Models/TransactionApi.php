<?php


namespace App\Models;


use Illuminate\Support\Facades\Log;
use tpayLibs\src\_class_tpay\Utilities\TException;

class TransactionApi extends \tpayLibs\src\_class_tpay\TransactionApi
{
    private $trId;

    public function __construct()
    {
        $this->merchantSecret = env('TPAY_MERCHANT_SECRET');
        $this->merchantId = (int)env('TPAY_MERCHANT_ID');
        $this->trApiKey = env('TPAY_TR_API_KEY');
        $this->trApiPass = env('TPAY_TR_API_PASS');
        parent::__construct();
    }

    public function getTransaction()
    {
        /**
         * Get info about transaction
         */

        $transactionId = $this->trId;

        try {
            return $this->setTransactionID($transactionId)->get();
        } catch (TException $e) {
            return false;
        }

    }

    public function createTransaction(float $amount, string $description, string $email, string $name, string $orderId, string $resultUrl, string $returnUrl)
    {
        /**
         * Create new transaction
         */

        $config = array(
            'amount' => $amount,
            'description' => $description,
            'crc' => $orderId,
            'result_url' => $resultUrl,
            'result_email' => 'kontakt@milionserc.pl',
            'return_url' => $returnUrl,
            'email' => $email,
            'name' => $name,
            'group' => 150,
            'accept_tos' => 1,
        );
        try {
            $res = $this->create($config);
            return $res['url'];
        } catch (TException $e) {
            return false;
            Log::debug('Tpay error', $e);
        }

    }
}
