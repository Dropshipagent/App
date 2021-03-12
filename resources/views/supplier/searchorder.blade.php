@extends('supplier.layouts.app')
@section('title', 'Store Orders')
@section('main-content')
<!-- include tags css -->
<section class="content-header">
    <h1>
        Search
        <small>orders for {{ $user->username }}</small>
    </h1>
</section>
@php
$invoiceTotal = 0
@endphp
<!-- Main content -->
<section class="content">
    <div class="box box-warning">
        <div class="box-header with-border">
            <table border="0" width="100%">
                <tr>
                    <td>
                        <div class="col-md-12">
                            {!! Form::open(array('id' => '','files'=>true,'method'=>'GET')) !!}
                            <?php
                            if (isset($_REQUEST['search'])) {
                                $searchKey = $_REQUEST['search'];
                            } else {
                                $searchKey = "";
                            }
                            ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Enter Order-ID</strong>
                                    <input id="tags" name="search" class="form-control" value="<?php echo $searchKey; ?>" required="required">
                                </div>    
                            </div>    
                            <div class="col-md-4">
                                <br>
                                <button type="submit" id="" class="btn btn-primary">Submit</button> 
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </td>
                    <td>
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="box-body table-responsive no-padding">
                @if(!empty($order))
                {!! Form::open(array('url' => 'supplier/create_invoice','id' => 'invoiceForm','files'=>true,'method'=>'POST')) !!}
                {!! Form::hidden('supplier_id', $user->id) !!}
                {!! Form::hidden('store_domain', $order->store_domain) !!}
                {!! Form::hidden('order_id', $order->order_id) !!}
                {!! Form::hidden('order_number', $order->order_number) !!}
                <table class="table table-hover">
                    <tr>
                        <th>ID</th>
                        <th>Order ID</th>
                        <th>Order Number</th>
                        <th>Customer Email</th>
                        <th>Customer Name</th>
                        <th>Payment Gateway</th>
                        <th>Financial Status</th>
                        <th>Order Value</th>
                        <th>Ship To</th>
                        <th>Created</th>
                    </tr>
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->email }}</td>
                        <td>{{ $order->cust_fname }}</td>
                        <td>{{ $order->payment_gateway }}</td>
                        <td>{{ $order->financial_status }}</td>
                        <td>{{ $order->order_value }}</td>
                        <td>{{ $order->ship_to }}</td>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                </table>
                <div class="box box-warning">
                    <div class="box-body">
                        <table class="table table-hover">
                            <tr>
                                <th>Item Name</th>
                                <th>Qty.</th>
                                <th>Price</th>
                                <th>Your Price</th>
                            </tr>
                            <?php
                            $invoiceTotal = 0;
                            $invoicesubTotal = 0;
                            ?>
                            @foreach($orderItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ ($item->quantity*$item->price) }}</td>
                                <td>
                                    <?php
                                    if (isset($item->productdetail->base_price) && $item->productdetail->product_status == 3) {
                                        $basePriceArr = json_decode($item->productdetail->base_price, true);
                                        $variantPriceByAdmin = $basePriceArr[$item->variant_id];

                                        $adminComisonArr = json_decode($item->productdetail->admin_commission, true);
                                        $variantCommissionByAdmin = $adminComisonArr[$item->variant_id];
                                    } else {
                                        $variantPriceByAdmin = 0;
                                        $variantCommissionByAdmin = 0;
                                    }
                                    $invoiceTotal += ($variantPriceByAdmin * $item->quantity);
                                    $invoicesubTotal = ($variantPriceByAdmin * $item->quantity);
                                    $variantCommissionByAdmin = ($variantCommissionByAdmin * $item->quantity);
                                    echo Form::hidden('commission_data[]', $variantCommissionByAdmin);
                                    echo Form::hidden('variant_id[]', $item->variant_id);
                                    echo Form::number('variant_supplier_price[]', $invoicesubTotal, array('step' => 'any', 'min' => '0', 'max' => $invoicesubTotal, 'readonly', 'required' => 'required', 'class' => 'form-control'));
                                    ?>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"></td>
                                <td><strong>Invoice Total : </strong>{{ $invoiceTotal }}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div class="col-md-12">
                    <div class="col-md-1"><h5>Notes</h5></div>
                    <div class="col-md-11">
                        <?php
                        echo Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 4]);
                        ?>
                    </div>
                </div>

                <div class="col-md-12 text-right">
                    <br>
                    <button type="button" id="" class="btn btn-primary btnCreateInvoice">Create Invoice</button> 
                </div>
                {!! Form::close() !!}
                @else
                @if($searchKey)
                <div class="text-center"><h4>Record not found!</h4></div>
                @endif
                @endif
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</section>
<!-- /.content -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.btnCreateInvoice').on('click', function () {
            var validateData = "{{ $invoiceTotal }}";
            if (validateData > 0) {
                var r = confirm("Are you sure?");
                if (r == true) {
                    $("#invoiceForm").submit();
                }
            } else {
                alert("Invoice total should be greater than 0.");
                return false;
            }
        });
    });
</script>
@endsection