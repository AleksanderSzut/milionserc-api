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
            Log::info(json_encode($notification));
        } catch (TException $e) {
            Log::info("Nie udało się");
        }
    }
}
