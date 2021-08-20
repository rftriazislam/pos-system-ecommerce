@extends('layouts/contentLayoutMaster')

@section('content')

    <div class="card">
        <div class="card-header border-bottom p-1">
            <h4 class="">{{ translate('Order Details') }}</h4>
        </div>

    	<div class="card-body mt-1">
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                @endphp
                <div class="col-md-3 ml-auto">
                    <label class="mb-50" for="update_payment_status">{{translate('Payment Status')}}</label>
                    <select class="form-control "  data-minimum-results-for-search="Infinity" id="update_payment_status">
                        <option value="paid" @if ($payment_status == 'paid') selected @endif>{{translate('Paid')}}</option>
                        <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>{{translate('Unpaid')}}</option>
                    </select>
                </div>
                <div class="col-md-3 ml-auto">
                    <label class="mb-50" for="update_delivery_status">{{translate('Delivery Status')}}</label>
                    @if($delivery_status != 'delivered' && $delivery_status != 'cancelled')
                        <select class="form-control "  data-minimum-results-for-search="Infinity" id="update_delivery_status">
                            <option value="pending" @if ($delivery_status == 'pending') selected @endif>{{translate('Pending')}}</option>
                            <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>{{translate('Confirmed')}}</option>
                            <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>{{translate('Picked Up')}}</option>
                            <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>{{translate('On The Way')}}</option>
                            <!--<option value="on_delivery" @if ($delivery_status == 'on_delivery') selected @endif>{{translate('On delivery')}}</option>-->
                            <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>{{translate('Delivered')}}</option>
                            <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>{{translate('Cancel')}}</option>
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ $delivery_status }}">
                    @endif
                </div>
            </div>
            <div class="row gutters-5">
                <div class="col  text-md-left">
                  <address>
                      <strong class="text-main">{{ json_decode($order->shipping_address)->name }}</strong><br>
                       {{ json_decode($order->shipping_address)->email }}<br>
                       {{ json_decode($order->shipping_address)->phone }}<br>
                       {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, {{ json_decode($order->shipping_address)->postal_code }}<br>
                       {{ json_decode($order->shipping_address)->country }}
                    </address>
                      @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                          <br>
                          <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                          Name: {{ json_decode($order->manual_payment_data)->name }}, Amount: {{ single_price(json_decode($order->manual_payment_data)->amount) }}, TRX ID: {{ json_decode($order->manual_payment_data)->trx_id }}
                          <br>
                          <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" target="_blank"><img src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt="" height="100"></a>
                      @endif
                </div>
                <div class="col-md-4 ml-auto">
                  <table>
                      <tbody>
                        <tr>
                            <td class="text-main text-bold">{{translate('Order #')}}</td>
                            <td class="text-right text-info text-bold">{{ $order->code }}</td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold">{{translate('Order Status')}}</td>
                                @php
                                  $status = $order->orderDetails->first()->delivery_status;
                                @endphp
                            <td class="text-right">
                                @if($status == 'delivered')
                                    <span class="badge badge-inline badge-success">{{ translate(ucfirst(str_replace('_', ' ', $status))) }}</span>
                                @else
                                    <span class="badge badge-inline badge-info">{{ translate(ucfirst(str_replace('_', ' ', $status))) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold">{{translate('Order Date')}}</td>
                            <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold">{{translate('Total amount')}}</td>
                            <td class="text-right">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold">{{translate('Payment method')}}</td>
                            <td class="text-right">{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                        </tr>
                      </tbody>
                  </table>
                </div>
            </div>
    		<div class="invoice-bill row">
    			<div class="col-sm-6">

    			</div>
    			<div class="col-sm-6">

    			</div>
    		</div>
    		<hr class="new-section-sm bord-no">
    		<div class="">
				<table class="table table-bordered aiz-table invoice-summary">
    				<thead>
        				<tr class="bg-trans-dark">
                            <th data-breakpoints="lg" class="min-col">#</th>
                            <th width="10%">{{translate('Photo')}}</th>
          					<th class="text-uppercase">{{translate('Description')}}</th>
                            <th data-breakpoints="lg" class="text-uppercase">{{translate('Delivery Type')}}</th>
          					<th data-breakpoints="lg" class="min-col text-center text-uppercase">{{translate('Qty')}}</th>
          					<th data-breakpoints="lg" class="min-col text-center text-uppercase">{{translate('Price')}}</th>
          					<th data-breakpoints="lg" class="min-col text-right text-uppercase">{{translate('Total')}}</th>
        				</tr>
    				</thead>
    				<tbody>
                        @php
                        $admin_user_id = \App\User::where('user_type', 'admin')->first()->id;
                        @endphp
                        @foreach ($order->orderDetails->where('seller_id', $admin_user_id) as $key => $orderDetail)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                    @if ($orderDetail->product != null)
                                      <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"><img height="50px" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                                    @else
                                      <strong>{{ translate('N/A') }}</strong>
                                    @endif
                                </td>
                                <td>
                                    @if ($orderDetail->product != null)
                                      <strong><a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-muted">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                      <small>{{ $orderDetail->variation }}</small>
                                    @else
                                      <strong>{{ translate('Product Unavailable') }}</strong>
                                    @endif
                                </td>
                                <td>
                                    @if ($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery')
                                      {{ translate('Home Delivery') }}
                                    @elseif ($orderDetail->shipping_type == 'pickup_point')
                                      @if ($orderDetail->pickup_point != null)
                                        {{ $orderDetail->pickup_point->getTranslation('name') }} ({{ translate('Pickup Point') }})
                                      @else
                                        {{ translate('Pickup Point') }}
                                      @endif
                                    @endif
                                </td>
                                <td class="text-center">{{ $orderDetail->quantity }}</td>
                                <td class="text-center">
                                    {{ single_price($orderDetail->price/$orderDetail->quantity) }}
                                </td>
                                <td class="text-center">{{ single_price($orderDetail->price) }}</td>
                            </tr>
                        @endforeach
    				</tbody>
				</table>
    		</div>
    		<div class="clearfix float-end">
    			<table class="table">
          			<tbody>
            			<tr>
            				<td><strong class="text-muted">{{translate('Sub Total')}} :</strong></td>
            				<td>
            					{{ single_price($order->orderDetails->sum('price')) }}
            				</td>
            			</tr>
            			<tr>
            				<td><strong class="text-muted">{{translate('Tax')}} :</strong></td>
            				<td>{{ single_price($order->orderDetails->sum('tax')) }}</td>
            			</tr>
                        <tr>
            				<td><strong class="text-muted"> {{translate('Shipping')}} :</strong></td>
            				<td>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
            			</tr>
                        <tr>
            				<td>
            					<strong class="text-muted">{{translate('Coupon')}} :</strong>
            				</td>
            				<td>
            					{{ single_price($order->coupon_discount) }}
            				</td>
            			</tr>
            			<tr>
            				<td><strong class="text-muted">{{translate('TOTAL')}} :</strong></td>
            				<td class="text-muted h5">
            					{{ single_price($order->grand_total) }}
            				</td>
            			</tr>
          			</tbody>
    			</table>
                <div class="text-end no-print">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light"><i data-feather='printer'></i></a>
                </div>
    		</div>
    	</div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#update_delivery_status').on('change', function(){
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
            });
        });

        $('#update_payment_status').on('change', function(){
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
            });
        });
    </script>
@endsection
