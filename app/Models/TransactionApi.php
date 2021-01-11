<?php


namespace App\Models;


use Illuminate\Support\Facades\Log;
use tpayLibs\src\_class_tpay\Utilities\TException;

class TransactionApi extends \tpayLibs\src\_class_tpay\TransactionApi
{
    private $trId;

    public function __construct()
    {
        $this->merchantSecret = 'demo';
        $this->merchantId = 1010;
        $this->trApiKey = '75f86137a6635df826e3efe2e66f7c9a946fdde1';
        $this->trApiPass = 'p@$$w0rd#@!';
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
            Log::debug( $e);
            return false;
        }

    }
}
