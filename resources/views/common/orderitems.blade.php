<?php
extract($shipping_address);
?>
<div>Order:: <strong>{{ $orderData->order_number }}</strong></div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <table class="table table-hover">
            <tr>
                <th>Item Name</th>
                <th>Qty.</th>
                <th>Price</th>
            </tr>
            @foreach($orderData->itemsarr as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td><?php
                    $subtotal_amount = ($item->quantity * $item->price);
                    echo currency($subtotal_amount, 'USD', currency()->getUserCurrency());
                    ?></td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">SHIPPING ADDRESS</h3>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body text-capitalize">
                {{ $name }}<br>
                @if($company)
                {{ $company?$company:"" }}<br>
                @endif
                {{ $address1." ".$address2 }}<br>
                {{ $province }}<br>
                {{ $city." ".$province_code }}<br>
                {{ $zip }}<br>
                {{ $shipping_phone }}<br>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>