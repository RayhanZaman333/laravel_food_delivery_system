@extends('admin/layouts/' . 'master')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">
        <div class="card">
            <div class="card-header">

                <h3 class="m-0 no-print">Order view</h3>
                <h3 class="m-0 d-none only-print">Invoice</h3>

                <div class="d-flex Order no-print">

                        <a href="" onclick="print()" class="btn-sm btn-info"> <i class="fas fa-print text-white"></i> Print Invoice </a>
                    </div>

            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <th width='25%'><h5 class="m-0">Invoice</h5></th>
                        <td>{{ $order->invoice_id }}</td>
                      </tr>
                      <tr>
                        <th width='25%'><h5 class="m-0">Customer Name</h5></th>
                        <td>{{ $order->customer->fullname }}</td>
                      </tr>

                      <tr>
                        <th width='25%'><h5 class="m-0">Customer Phone</h5></th>
                        <td>{{ $order->customer->phone }}</td>
                      </tr>

                      <tr>
                        <th width='25%'><h5 class="m-0">Customer Location</h5></th>
                        <td><a href="{{'https://www.google.com/maps/search/?api=1&query='.$order->lat.','.$order->lon}}"
                            onclick="window.open('{{'https://www.google.com/maps/search/?api=1&query='.$order->lat.','.$order->lon}}','popup','width=800,height=600'); return false;"
                             class="btn-sm btn-info  mr-4 no-print"> <i class="fas fa-map"></i> Google Map</a> {{ $order->useraddress->address }}  </td>
                      </tr>

                      <tr>
                        <th width='25%'><h5 class="m-0">Payment Method</h5></th>
                        <td>{{ $order->orderPaymentMethod->payment_method }}</td>
                      </tr>

                    </tbody>
                  </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="m-0">Orders</h3>

            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                      <thead>

                          <th width='20%''>Restaurant</th>
                          <th width='30%'>Products</th>
                          <th>Quantity</th>
                          <th>Unit Price</th>
                          <th>Addons</th>
                      </thead>
                      @php
                          $totalAmount = 0;
                      @endphp


                        @foreach ($order->orderItems as $orderItem)
                            @php
                                if( $orderItem->productByOrder->discount_price > 0){
                                    $totalAmount += $orderItem->productByOrder->discount_price*$orderItem->quantity;
                                }else{
                                    $totalAmount += $orderItem->productByOrder->unit_price*$orderItem->quantity;
                                }
                            @endphp
                            <tr>
                                <td>{{ $orderItem->singleRestaurant->name }}</td>
                                <td>{{ $orderItem->productByOrder->name}}</td>
                                <td>{{ $orderItem->quantity}}</td>
                                <td>
                                    @if ($orderItem->productByOrder->discount_price > 0)
                                        {{ $orderItem->productByOrder->discount_price }}
                                    @else
                                        {{ $orderItem->productByOrder->unit_price }}
                                    @endif
                                </td>

                                <td>
                                    @foreach ( $orderItem->orderProductAddons as $addon)
                                        @php
                                            $totalAmount += $addon->orderAddons->addon_price;
                                        @endphp
                                        <span class="btn-sm btn-primary m-1">{{ $addon->orderAddons->addon_name }}</span>

                                        <span class="btn-sm btn-dark m-1">{{ $addon->orderAddons->addon_price }} <span class="ml-5">৳</span></span>
                                        <br>
                                        <br>
                                    @endforeach
                                </td>
                            </tr>
                         @endforeach

                            <tr>
                                <td><b>Note:</b> </td>
                                <td colspan="5">{{ $order->order_note ? $order->order_note : '-'  }}</td>
                            </tr>

                            <tr>
                                <td colspan="4">Total: </td>
                                <td>{{ $totalAmount }} <span class="ml-5">৳</span></td>
                            </tr>

                         <tr class="no-print">
                            <td colspan="4">Action: </td>
                            <td class="text-center">
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                        <select name="status" id="" style="width: 210px; height: 34px;
                                        border: 2px solid #dadada !important;
                                        border-radius: 5px;margin-right: 5px;">
                                            @foreach ($status as $state)
                                        <option {{ $state->id == $order->status->order_status_id ? 'selected':'' }}  value="{{ $state->id }}">{{ $state->status_type }}</option>
                                    @endforeach
                                    </select>
                                    <button type="submit" class="btn-sm btn-primary">update</button>
                                </form>
                            </td>
                         </tr>
                  </table>
                    </tbody>
            </div>
        </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
