@extends('shipper.layouts.app')
@section('title', 'Invoice Detail')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Invoices Detail
        <small>list of invoice items.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/shipper/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('/shipper/stores') }}">Mapped Stores</a></li>
    </ol>
</section>

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
                        <div class="invoice_logo">
                            <img src="{{url('admin/dist/img/logo.png')}}" >  
                        </div>
                    </td>
                    <td align="right" class="campanyinfo">
                        <div class="invoice_date"><strong>Date: </strong><span>{{ date('M d, Y H:i:s', strtotime($mainInvoice->created_at)) }}</span></div>
                        <div class="head">{{ $storeData->username }}</div>
                        <div class="info">{{ $storeData->email }}</div>
                        <div class="info">{{ $storeData->address }}</div>
                    </td>
                </tr>
            </table>
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
                            <td class="price">{{ ($oVal['product_admin_price']/$oVal['product_quantity']) }}</td>
                            <td class="quantity"> {{ $oVal['product_quantity'] }}</td>
                            <td class="amount" style="background: #e7e8e9;">{{ number_format(($oVal['product_admin_price']), 2) }}</td>
                        </tr>
                        <?php
                        $main_invoice_total += $oVal['product_admin_price'];
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
                </table>

            </main>
        </div>
    </div>
</section>
@endsection