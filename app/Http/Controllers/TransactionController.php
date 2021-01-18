<?php

namespace App\Http\Controllers;

use App\Jobs\orderJob;
use App\Mail\CreateConfession;
use App\Models\Confession;
use App\Models\Order;
use App\Models\Payment;
use App\Models\TransactionNotificationApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use tpayLibs\src\_class_tpay\Utilities\TException;

class TransactionController extends Controller
{
    protected function createCart($cart) {

    }
    public function create(Request $request)
    {
        try {

            $notificationApi = new TransactionNotificationApi();

            $notification = $notificationApi->getTpayNotification();

            if ($notification['tr_status'] == 'TRUE' && $notification['tr_error'] == 'none') {
            $payment = Payment::where('transaction_title', $notification['tr_id'])->first();

                if ($payment !== null) {

                    if ($payment->status === Payment::STATUS_WAITING) {

                        $payment->status = Payment::STATUS_PAID;
                        $order = $payment->order;
                        $order->status= Order::STATUS_ACTIVE;

                        $order->save();
                        $payment->save();

                        $createConfessionLinks = [];

                        foreach ($payment->order->cart as $cart) {
                            $confession = new Confession();
                            $confession->package()->associate($cart->package);
                            $confession->uuid = Str::uuid();
                            $confession->title = null;
                            $confession->content = null;
                            $confession->public = Confession::PUBLIC_NO;
                            $confession->access_code = Str::random(30);
                            $confession->status = Confession::STATUS_NO_CREATED;

                            $confession->order()->associate($order);

                            $createConfessionLinks[] = env("APP_URL") . '/stworz-wyznanie/' . $confession->uuid . '/' . $confession->access_code;

                            $confession->save();
                        }

                        $email = $order->billing->email;
                        $name = $order->billing->full_name;

                        $this->transactionPaid($email, $createConfessionLinks, $name);

                        Log::info("Payment successful");
                        return response()->json([
                            'status' => 'PAYMENT_SUCCESSFUL',
                            'statusCode' => 1,
                            'statusMessage' => "Payment successful."
                        ])->setStatusCode(Response::HTTP_OK);
                    } else {
                        Log::warning(json_encode($notification));
                        Log::critical("payment was made earlier");
                        return response()->json([
                            'status' => 'PAYMENT_WAS_MADE_EARLIER',
                            'statusCode' => 0,
                            'statusMessage' => "Payment was made earlier."
                        ])->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
                    }
                } else {
                    Log::warning(json_encode($notification));
                    Log::critical("Payment not exist");
                    return response()->json([
                        'status' => 'PAYMENT_NOT_EXIST',
                        'statusCode' => 0,
                        'statusMessage' => "Payment not exist."
                    ])->setStatusCode(Response::HTTP_NOT_FOUND);
                }
            } else {
                Log::warning(json_encode($notification));
                Log::critical("Payment notification status false");
                return response()->json([
                    'status' => 'PAYMENT_NOTIFICATION_STATUS_FALSE',
                    'statusCode' => 0,
                    'statusMessage' => 'Payment notification status false'
                ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (TException $e) {
            Log::critical("Payment notification error");
            Log::debug($e);
            return response()->json([
                'status' => false,
                'statusCode' => $e->getCode(),
                'statusMessage' => $e->getMessage()
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

    }

    function transactionPaid($email, $createConfessionLinks, $name)
    {
        $mail = new CreateConfession($createConfessionLinks, $name);
        Mail::to($email)->send($mail);
    }
}
