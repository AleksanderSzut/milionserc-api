<?php

namespace App\Http\Controllers;

use App\Jobs\Mail\OrderJob;
use App\Models\AdditionalAttributeCart;
use App\Models\AdditionalCostsPackage;
use App\Models\AdditionalPackageAttribute;
use App\Models\Billing;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Shipping;
use App\Rules\CartItems;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class OrderController extends Controller
{
    public function index(): array
    {
        $orders = Order::all();


        return [$orders];

    }

    protected function rules(): array
    {
        return [
            'cartItems.*' => ['required', new CartItems],
            'cartItems' => ['required'],

            'billingAddress.fullName' => ['required', 'max: 255'],
            'billingAddress.city' => ['required', 'max:255'],
            'billingAddress.country' => ['regex:/^[a-zA-Z]{2,}/', 'required'],
            'billingAddress.region' => ['required', 'max:255'],
            'billingAddress.streetAddress' => ['required', 'max:255'],
            'billingAddress.zipCode' => ['required', 'max:255'],
            'billingAddress.phoneNumber' => ['required', 'max:255'],
            'billingAddress.email' => ['required', 'email'],
            'billingAddress.orderRemark' => [ 'max: 65000'],
            'terms' => ['accepted'],

            'shippingAddress.name' => ['required_with:shippingAddress', 'max: 255'],
            'shippingAddress.country' => ['required_with:shippingAddress', 'regex:/^[a-zA-Z]{2,}/'],
            'shippingAddress.streetAddress' => ['required_with:shippingAddress', 'max: 255'],
            'shippingAddress.city' => ['required_with:shippingAddress', 'max: 255'],
            'shippingAddress.zipCode' => ['required_with:shippingAddress', 'max: 255'],
            'shippingAddress.phoneNumber' => ['required_with:shippingAddress', 'max: 255'],
        ];
    }

    protected function addShippingData(Request $request): Shipping
    {
        $shipping = new Shipping;

        $shipping->name = $request['shippingAddress']['name'];
        $shipping->country = $request['shippingAddress']['country'];
        $shipping->street_address = $request['shippingAddress']['streetAddress'];
        $shipping->city = $request['shippingAddress']['city'];
        $shipping->zip_code = $request['shippingAddress']['zipCode'];
        $shipping->phone_number = $request['shippingAddress']['phoneNumber'];
        $shipping->save();

        return $shipping;
    }

    protected function addBillingData(Request $request): Billing
    {
        $billing = new Billing;

        $billing->full_name = $request['billingAddress']['fullName'];
        $billing->city = $request['billingAddress']['city'];
        $billing->country = $request['billingAddress']['country'];
        $billing->region = $request['billingAddress']['region'];
        $billing->street_address = $request['billingAddress']['streetAddress'];
        $billing->zip_code = $request['billingAddress']['zipCode'];
        $billing->phone_number = $request['billingAddress']['phoneNumber'];
        $billing->email = $request['billingAddress']['email'];

        if (isset($request['billingAddress']['orderRemark']))
            $billing->order_remark = $request['billingAddress']['orderRemark'];

        if (isset($request['billingAddress']['taxId']))
            $billing->tax_id = $request['billingAddress']['taxId'];

        $billing->save();

        return $billing;
    }

    protected function calculateThePrice(Request $request): int
    {
        $toPay = 0;
        foreach ($request['cartItems'] as $value) {

            $cart = new Cart();
            $cart->package()->associate(Package::find($value['packageId']));

            $toPay += $cart->package->additionalCostsPackage->sum("price");
            $toPay += $cart->package->price;
            $toPay += $cart->package->shipping_price;

            if (isset($value['additionals'])) {
                foreach ($value['additionals'] as $additional) {
                    $additionalAttributeCart = new AdditionalAttributeCart();

                    $additionalPackageAttribute = AdditionalPackageAttribute::find($additional['id']);

                    $toPay += $additionalPackageAttribute->price;

                    $additionalAttributeCart->AdditionalPackageAttribute()->associate($additionalPackageAttribute);
                    $cart->additionalAttributeCart[] = $additionalAttributeCart;
                }
            }
        }
        return $toPay;
    }

    protected function addPaymentData(Request $request): Payment
    {
        $payment = new Payment();

        $payment->to_pay = $this->calculateThePrice($request);

        $payment->status = Payment::STATUS_WAITING;

        $payment->save();

        return $payment;
    }

    protected function addCartData(Request $request, Order $order): bool
    {
        $allOk = true;
        foreach ($request['cartItems'] as $value) {

            $cart = new Cart();

            $cart->package()->associate(Package::find($value['packageId']));
            $cart->order()->associate($order);

            if ($cart->save())
                $allOk = false;

            if (isset($value['additionals'])) {
                foreach ($value['additionals'] as $additional) {
                    $additionalAttributeCart = new AdditionalAttributeCart();

                    $additionalPackageAttribute = AdditionalPackageAttribute::find($additional['id']);

                    $additionalAttributeCart->AdditionalPackageAttribute()->associate($additionalPackageAttribute);
                    $additionalAttributeCart->cart()->associate($cart);

                    if (!$additionalAttributeCart->save())
                        $allOk = false;
                }
            }
        }
        return $allOk;
    }

    protected function failsCreate($message)
    {
        return response()->json([
            'status' => 'ORDER_SERVER_ERROR',
            'statusCode' => 0,
            'statusMessage' => $message
        ])->setStatusCode(422);
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {

            return response()->json([
                'status' => 'ORDER_VALIDATION_ERROR',
                'statusCode' => 0,
                'statusMessage' => $validator->errors()
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        } else if (isset($request['billingAddress']['companyName']) && isset($request['billingAddress']['fullName'])) {
            return response()->json([
                'status' => 'ORDER_VALIDATION_ERROR',
                'statusCode' => 0,
                'statusMessage' => "Can't set full name field with company name."
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $order = new Order;

            if (isset($request['shippingData'])) {
                $shipping = $this->addShippingData($request);
                $order->shipping()->associate($shipping);
            }
            $billing = $this->addBillingData($request);
            $payment = $this->addPaymentData($request);


            $order->status = Order::STATUS_NOT_PAID;

            $order->billing()->associate($billing);
            $order->payment()->associate($payment);

            $order->push();

            $payment->createTransaction();
            $transactionLink = $payment->getTransactionLink();

            $this->addCartData($request, $order);

            $this->ordered($order, $transactionLink);

            return response()->json([
                'status' => 'ORDER_SUCCESSFUL',
                'statusCode' => 2,
                'statusMessage' => 'Order added successful',
                'data' => [
                    'transactionLink' => $transactionLink,
                ]
            ], Response::HTTP_OK);
        }
    }

    protected function ordered(Order $order, $transactionLink)
    {
        $email = $order->billing->email;
        $payment = $order->payment;
        $toPay = $payment->to_pay / 100;
        $fullName = $order->billing->full_name;

        dispatch_now(new orderJob($email, $transactionLink, $fullName, $toPay));
    }

    public function store(Request $request)
    {
    }
}
