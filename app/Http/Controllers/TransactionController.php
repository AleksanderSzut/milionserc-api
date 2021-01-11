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
        try {
            $notificationApi = new TransactionNotificationApi();
            $notification = $notificationApi->getTpayNotification();

            if ($notification['tr_status']=='TRUE' && $notification['tr_error']=='none') {
                Log::info(json_encode($notification));
                Log::info("Payment successful");
            }
            else
            {
                Log::critical("Payment notification status false");
            }
        } catch (TException $e) {
            Log::critical("Payment notification error");
            Log::debug($e);
        }
    }
}
