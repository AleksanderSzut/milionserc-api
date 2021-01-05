<?php

namespace App\Http\Controllers;

use App\Models\AdditionalAttributeCart;
use App\Models\AdditionalCostsPackage;
use App\Models\AdditionalPackageAttribute;
use App\Models\Billing;
use App\Models\Cart;
use App\Models\Confession;
use App\Models\Order;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Shipping;
use App\Rules\PackageAdditionals;
use App\Rules\PackageId;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Post;
use Illuminate\Http\Request;


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
            'cartItems.*.packageId' => ['required', new PackageId],
            'cartItems.*.additionals.*.id' => ['required', new PackageAdditionals()],

            'billingAddress.fullName' => ['regex: /^[a-zA-Z]{4,}(?: [a-zA-Z]+){0,2}$/', 'required'],
            'billingAddress.city' => ['required', 'max:255'],
            'billingAddress.country' => ['regex:/^[a-zA-Z]{2,}/', 'required'],
            'billingAddress.region' => ['required', 'max:255'],
            'billingAddress.streetAddress' => ['required', 'max:255'],
            'billingAddress.zipCode' => ['required', 'max:255'],
            'billingAddress.phoneNumber' => ['required', 'max:255'],
            'billingAddress.email' => ['required', 'email'],
            'billingAddress.orderRemark' => ['required', 'max: 65000'],
            'billingAddress.taxId' => ['max: 255', 'required_with:billingAddress.companyName'],
            'billingAddress.companyName' => ['max: 255', 'required_with:billingAddress.taxId'],

            'shippingAddress.name' => ['required', 'max: 255'],
            'shippingAddress.country' => ['regex:/^[a-zA-Z]{2,}/', 'required'],
            'shippingAddress.streetAddress' => ['required', 'max: 255'],
            'shippingAddress.city' => ['required', 'max: 255'],
            'shippingAddress.zipCode' => ['required', 'max: 255'],
            'shippingAddress.phoneNumber' => ['required', 'max: 255'],
        ];
    }


    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return $validator->errors();
        } else {
            $order = new Order;

            // <editor-fold desc="shipping">
            $shipping = new Shipping;

            $shipping->name = $request['shippingAddress']['name'];
            $shipping->country = $request['shippingAddress']['country'];
            $shipping->street_address = $request['shippingAddress']['streetAddress'];
            $shipping->city = $request['shippingAddress']['city'];
            $shipping->zip_code = $request['shippingAddress']['zipCode'];
            $shipping->phone_number = $request['shippingAddress']['phoneNumber'];

            //</editor-fold>

            // <editor-fold desc="billing">
            $billing = new Billing;

            $billing->full_name = $request['billingAddress']['fullName'];
            $billing->city = $request['billingAddress']['city'];
            $billing->country = $request['billingAddress']['country'];
            $billing->region = $request['billingAddress']['region'];
            $billing->street_address = $request['billingAddress']['streetAddress'];
            $billing->zip_code = $request['billingAddress']['zipCode'];
            $billing->phone_number = $request['billingAddress']['phoneNumber'];
            $billing->email = $request['billingAddress']['email'];
            $billing->order_remark = $request['billingAddress']['orderRemark'];
            $billing->tax_id = $request['billingAddress']['taxId'];


            //</editor-fold>

            // <editor-fold desc="payment">

            $payment = new Payment();

            // </editor-fold>

            $toPay = false;

            foreach ($request['cartItems'] as $value) {

                $cart = new Cart();
                $cart->package = Package::find($value['packageId']);

                $toPay += $cart->package->additionalCostsPackage->sum("price");
                $toPay += $cart->package->price;
                $toPay += $cart->package->shipping_price;

                $additionalAttributeCart = new AdditionalAttributeCart();

                foreach ($value['additionals'] as $additional)
                {
                    $additionalPackageAttribute = AdditionalPackageAttribute::find($additional['id']);
                    $toPay += $additionalPackageAttribute->price;

                    $additionalAttributeCart->AdditionalPackageAttribute = $additionalPackageAttribute;
                    $cart->additionalAttributeCart = new AdditionalAttributeCart();
                }


            }
            $payment->to_pay = $toPay;
            $payment->status = 0;

            return "good";

        }

    }

    public function store(Request $request)
    {
    }
}
