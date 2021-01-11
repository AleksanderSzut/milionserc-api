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
        Log::critical(print_r($request));
        try {
            $notificationApi = new TransactionNotificationApi();
            $notification = $notificationApi->getTpayNotification();
            Log::critical((string)$notification);
        } catch (TException $e) {
            Log::critical($e);
        }
    }
}
