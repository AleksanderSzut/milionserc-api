@extends('layouts.app', ['page' => __('Orders'), 'pageSlug' => 'orders'])

@section("content")

    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Zamówienia</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="">
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Status</th>
                                <th scope="col">Status płatności</th>
                                <th scope="col">Wartość</th>
                                <th scope="col">email</th>
                                <th scope="col">Data utworzenia</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <tr>
                                        <td>{{$order->id}}</td>
                                        <td>
                                            {{$order->getStatusText()}}
                                        </td>
                                        <td>
                                            {{$order->payment->getStatusText()}}
                                        </td>
                                        <td>
                                            {{$order->payment->to_pay/100}} PLN
                                        </td>
                                        <td>
                                            {{$order->billing->email}}
                                        </td>
                                        <td>
                                            {{$order->created_at}}
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#"
                                                   role="button"
                                                   data-toggle="dropdown" aria-haspopup="true"
                                                   aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div
                                                    class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="#order_{{$order->id}}_billing" data-toggle="collapse" >Dane płatności</a>
                                                    <a class="dropdown-item" href="#order_{{$order->id}}_shipping" data-toggle="collapse" >Dane wysyłki</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="order_{{$order->id}}_billing">
                                        <td colspan="6">
                                            <table class="table tablesorter ">
                                                <tbody>
                                                    <tr>
                                                        <th>Dane płatności: </th>
                                                        <td>{{$order->billing->full_name}}</td>
                                                        <td>{{$order->billing->country}}</td>
                                                        <td>{{$order->billing->region}}</td>
                                                        <td>{{$order->billing->city}}</td>
                                                        <td>{{$order->billing->street_address}}</td>
                                                        <td>{{$order->billing->zip_code}}</td>
                                                        <td>{{$order->billing->phone_number}}</td>
                                                        <td>{{$order->billing->tax_id}}</td>
                                                        <td>{{$order->billing->company}}</td>
                                                        <td>{{$order->billing->order_remark}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="order_{{$order->id}}_shipping">
                                        <td colspan="6">
                                            <table class="table tablesorter ">
                                                <tbody>
                                                    <tr>
                                                        <th>Dane wysyłki: </th>
                                                        <td>{{$order->shipping->name}}</td>
                                                        <td>{{$order->shipping->country}}</td>
                                                        <td>{{$order->shipping->city}}</td>
                                                        <td>{{$order->shipping->zip_code}}</td>
                                                        <td>{{$order->shipping->phone_number}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
