<?php


namespace App\Models;

use tpayLibs\src\_class_tpay\Notifications\BasicNotificationHandler;

class TransactionNotificationApi extends BasicNotificationHandler
{
    public function __construct()
    {
        $this->merchantSecret = 'demo';
        $this->merchantId = 1010;
        parent::__construct();

    }

    /**
     * @return array
     * @throws \tpayLibs\src\_class_tpay\Utilities\TException
     */
    public function getTpayNotification(): array
    {
        $this->enableForwardedIPValidation();
        return $this->checkPayment();
    }
}
/*
     * Example $paymentDetails response
    Array
    (
        [id] => 12345
        [tr_id] => TR-BRA-79FR0X
        [tr_date] => 2019-07-22 08:45:23
        [tr_crc] => order_200
        [tr_amount] => 40.96
        [tr_paid] => 40.96
        [tr_desc] => Tpay shop
        [tr_status] => TRUE
        [tr_error] => none
        [tr_email] => customer@example.com
        [test_mode] => 1
        [md5sum] => 0d1cf3083e2fe3b49d046c28e28d120c
    )
     */

