<?php

use App\Order;
use App\OrderItem;
use App\StoreInvoice;
?>
@extends('supplier.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
@include('supplier.layouts.header-tabs')
<!-- Main content -->
<section class="content spreadsheetdata-order-list">
    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table">
                    <section class="content-header">
                        <h1>New FulFillments</h1>
                    </section>
                </div>
               
                <div class="panel-body">
                    {!! Form::open(['method'=>'POST', 'url'=> 'supplier/updatetrackingdata', 'accept-charset'=>'UTF-8','files'=>true, 'id' => 'spreadsheetfrm']) !!}
                    
                    @if($requestdata->order_number  != '-1')
                    <input type="hidden" name="order_number" value="{{ $requestdata->order_number }}">
                    @endif

                    @if($requestdata->tracking_number != '-1')
                    <input type="hidden" name="tracking_number" value="{{ $requestdata->tracking_number }}">
                    @endif

                    @if($requestdata->tracking_url != '-1')
                    <input type="hidden" name="tracking_url" value="{{ $requestdata->tracking_url }}">
                    @endif

                    @if($requestdata->tracking_company != '-1')
                    <input type="hidden" name="tracking_company" value="{{ $requestdata->tracking_company }}">
                    @endif
                    
                    <div class="table-responsive supplier_page clearfix">
                        <table class="table table-hover">
                            <tr>
                                <th>Order Name</th>
                                <th>Tracking Company</th>
                                <th>Tracking Number</th>
                                <th>Tracking URL</th>
                                <th>Items</th>
                            </tr>
                            <?php
                            $sheetData = Session::get('spreadsheetData');
                            $storeDomain = helGetUsernameById(Session::get('selected_store_id'));
                            foreach ($sheetData as $dataSingle) {
                                if (!empty(trim($dataSingle[0]))) {
                                    //get invoice data of uploaded order id
                                    $getInvoice = StoreInvoice::where("order_number", $dataSingle[0])->where("fulfillment_status", "!=", "fulfilled")->where('store_domain', $storeDomain)->first();
                                    if ($getInvoice) {
                                        //get order data of uploaded order id
                                        $orderDtl = Order::with(['itemsarr'])->where("order_number", $dataSingle[0])->where('store_domain', $storeDomain)->first();
                                        //code to check which items will be able to fullfill
                                        $line_items = [];
                                        $supplierPriceArr = json_decode($getInvoice->invoice_data, true);
                                        foreach ($orderDtl->itemsarr as $item) {
                                            if ($supplierPriceArr[$item->variant_id] > 0) {
                                                $line_items[] = ["id" => $item->item_id];
                                            }
                                        }
                                        $orderItems = OrderItem::with(['productdetail'])->where("order_id", $getInvoice->orderdetail->order_id)->get();
                                        $variant_id_arr = [];
                                        $invoice_items = [];
                                        foreach ($orderItems as $item) {
                                            if ($supplierPriceArr[$item->variant_id] > 0) {
                                                if (in_array($item->variant_id, $variant_id_arr)) {
                                                    $invoice_items[$item->variant_id]['product_title'] = $item->productdetail->title;
                                                    $invoice_items[$item->variant_id]['product_quantity'] = ($invoice_items[$item->variant_id]['product_quantity'] + $item->quantity);
                                                } else {
                                                    $invoice_items[$item->variant_id]['product_title'] = $item->productdetail->title;
                                                    $invoice_items[$item->variant_id]['product_quantity'] = $item->quantity;
                                                }
                                                $variant_id_arr[] = $item->variant_id;
                                            }
                                        }
                            ?>
                                        <tr>
                                            <td>{{ $dataSingle[0] }}</td>
                                            <td>{{ $dataSingle[3] }}</td>
                                            <td>{{ $dataSingle[1] }}</td>
                                            <td>{{ $dataSingle[2] }}</td>
                                            <td>
                                                @if($invoice_items)
                                                <table class="table table-hover tracking-orderitems">
                                                    @foreach($invoice_items as $itemsdata)
                                                    <tr>
                                                        <th>{{$itemsdata['product_quantity']}}*</th>
                                                        <td>{{$itemsdata['product_title']}}</td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                                @endif
                                            </td>
                                        </tr>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 text-left">
                            <a class="btn btn-primary" href=" {{ url('supplier/showspreadsheetdata') }}  ">Back</a>
                        </div>
                        <div class="col-xs-6 text-right">
                            <button class="btn btn-primary" type="submit">FulFill</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#spreadsheetfrm').submit(function(e) {
            var validation = 0;
            $('.required').each(function() {
                var selfval = $(this);
                if (selfval.val() == '-1') {
                    validation = 1;
                    selfval.css('border', '1px solid red');
                }
            });
            if (validation == 1) {
                return false;
            } else {
                return true;
            }
        });
    });
</script>
@endsection