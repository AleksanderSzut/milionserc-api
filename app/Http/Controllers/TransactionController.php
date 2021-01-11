<?php

namespace App\Http\Controllers;

use App\Models\TransactionNotificationApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use tpayLibs\src\_class_tpay\Utilities\TException;

class TransactionController extends Controller
{
    public function create(Request $request)
    {
        $_POST = array
        (
            'id' => '12345',
            'tr_id' => 'TR-BRA-79FR0X',
            'tr_date' => '2019-07-22 08:45:23',
            'tr_crc' => 'order_200',
            'tr_amount' => '40.96',
            'tr_paid' => '40.96',
            'tr_desc' => 'Tpay shop',
            'tr_status' => 'TRUE',
            'tr_error' => 'none',
            'tr_email' => 'customer@example.com',
            'test_mode' => '1',
            'md5sum' => '0d1cf3083e2fe3b49d046c28e28d120c',
        );
        Log::critical($request);
        try {
            $notificationApi = new TransactionNotificationApi();
            $notification = $notificationApi->getTpayNotification();
            Log::critical((string)$notification);
        } catch (TException $e) {
            Log::critical($e);
        }
    }
}
