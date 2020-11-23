@extends('shipper.layouts.app')
@section('title', 'Invoice List')
@section('main-content')
<!-- Content Header (Page header) -->
@include('shipper.layouts.header-tabs')

<style>
    .invoiceDetails th {
        text-align: left;
        padding-left: 11px;
    }
    .invoiceDetails td {
        padding-left: 11px;
    }
    .amount{background: #e7e8e9; text-align: right; padding-right: 15px; font-size: 18px;}
</style>
<section class="content">
    <div class="nav-tabs-custom invoiceDetails" style="width: 1000px; margin: auto; font-size: 16px; padding:25px;">
        <div class="tab-content" >

            <table cellpadding="0" cellspacing="5" border="0" style="width:100% !important; margin-top: 25px;">    
                <tr class="topheading">
                    <td valign="top" style="width:150px">
                        <img src="{{url('admin/dist/img/logo.png')}}" >  
                    </td>
                    <td align="right" class="campanyinfo">
                        <div class="head">{{ $storeData->username }}</div>
                        <div class="info">{{ $storeData->email }}</div>
                        <div class="info">{{ $storeData->address }}</div>
                    </td>
                </tr>
            </table>
            <?php /*
              <table cellpadding="0" cellspacing="5" border="0" style="width:100% !important; margin-top: 10px;">
              <tr class="customerAddress">
              <td valign="top" style="color:#323534; padding-right: 50px;">
              Customer : &nbsp; &nbsp; <b>{{$order->cust_fname}}</b><br>
              Address:  &nbsp;  &nbsp; &nbsp; &nbsp; <b>{{$order->ship_to}}</b><br>
              </td>

              <td valign="top" align="right" width="270px">
              <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" >

              <tr>
              <td>Order No : </td><td align='right'><b>{{$order->order_number}}</b></td>
              </tr>
              <tr>
              <td>Credit Term : </td><td align='right'><b></b></td>
              </tr>
              </table>

              </td>
              </tr>
              </table> */ ?>
            <main>                
                <table cellpadding="0" cellspacing="0" border-color="#e7e8e9" class="listdata" style="width:100%;height:380px; margin-top: 25px;">    
                    <tr align="left">
                        <th style="border:1px solid #e7e8e9;border-right:none;border-left:none; width:30px; ">Item</th>
                        <th style="border:1px solid #e7e8e9;border-right:none;width:400px; ">Name</th>
                        <th style="border:1px solid #e7e8e9;border-right:none;width:100px; ">Price</th>
                        <th style="border:1px solid #e7e8e9;border-right:none; width:120px;">Quantity</th>
                        <th style="border:none; border-top:1px solid #e7e8e9; background: #e7e8e9; text-align: center; width: 70px;">Amount</th>
                    </tr>
                    <?php
                    $main_invoice_total = 0;
                    foreach ($invoice_items as $oKey => $oVal) {
                        ?>
                        <tr valign="center">
                            <td class="items">{{ $oKey }}</td>
                            <td class="description">{{ $oVal['product_title'] }}</td>
                            <td class="price">{{ $oVal['product_admin_price'] }}</td>
                            <td class="quantity"> {{ $oVal['product_quantity'] }}</td>
                            <td class="amount" style="background: #e7e8e9;">{{ number_format(($oVal['product_admin_price'] * $oVal['product_quantity']), 2) }}</td>
                        </tr>
                        <?php
                        $main_invoice_total += ($oVal['product_admin_price'] * $oVal['product_quantity']);
                    }
                    ?>
                    <tr valign="top">
                        <td colspan="3" style="border:none;border-top:1px solid #e7e8e9;">

                        </td>
                        <td style="border:none;border-top:1px solid #e7e8e9;position: relative; padding-top: 30px; padding-right: 15px; " align="right" class="netamount">
                            <div style="width:50px; height:3px; position: absolute;top:136px; right:-10px; color: #e7e8e9; background: #ff7900"></div>
                            <p style="font-size:22px; color:#000;">Net Amount</p>
                        </td>
                        <td style="border:none; background: #e7e8e9; text-align: right; font-size: 18px; color:#000;padding-top: 30px; padding-right: 15px;">
                            <p style="font-size:22px !important; color: #ff7900 "><b>{{number_format($main_invoice_total,2)}}</b></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" align="center" style="padding:20px">
                            @if(count($main_order_ids) > 0 && $main_invoice_total > 0)
                            {!! Form::open(array('url' => 'shipper/createbluckinvoice/'.$storeData->id,'id' => 'flag_submit','files'=>true,'method'=>'POST')) !!}
                            @foreach ($main_order_ids as $order_id)
                            <input type="hidden" class="flag_checkbox" name="flag[]" data-id="flagData" value="{{$order_id}}" />
                            @endforeach
                            <input type="submit" class="btn btn-danger create_invoice_btn" name="create_invoice" value="Create Invoice" />
                            {!! Form::close() !!}
                            @endif
                        </td>
                    </tr>
                </table>

            </main>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function () {
        //metod call on create invoice button
        $('.create_invoice_btn').on('click', function () {
            if (confirm('Please confirm, really you want to create an invoice!')) {
                $("#flag_submit").submit();
            }
        });
    });
</script>
@endsection