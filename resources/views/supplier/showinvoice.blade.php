@extends('supplier.layouts.app')
@section('title', 'Invoice List')
@section('main-content')
<!-- Content Header (Page header) -->
@include('supplier.layouts.header-tabs')

<style>
    .invoiceDetails th {
        text-align: left;
        padding-left: 11px;
    }
    .invoiceDetails td {
        padding-left: 11px;
    }
    .amount{ text-align: right; padding-right: 15px; font-size: 18px;}
</style>
<section class="content">
    <div class="nav-tabs-custom invoiceDetails" style="width: 1000px; margin: auto; font-size: 16px; padding:25px;">
        <div class="tab-content table-responsive" >

            <table cellpadding="0" cellspacing="5" border="0" style="width:100% !important; margin-top: 25px;">  
                <tr>
                    <td colspan="2" class="text-center header_txtt"> <h1 class="heading color-white"><span class="color_ye">DROPSHIP</span>&nbsp; AGENT <span class="sub_heads"><i>INVOICES</i></span></h1></td>
                </tr>  
                <tr class="topheading">
                    <td valign="top" style="width:150px">
                        <!-- <div class="invoice_logo">
                            <img src="{{url('admin/dist/img/logo.png')}}" >  
                        </div> -->                        
                    </td>
                    <td align="right" class="campanyinfo">
                        <div class="head"><strong>INVOICE: </strong>{{ $storeData->username }}</div>
                        <div class="info"><strong>INVOICE FOR: </strong>{{ $storeData->email }}</div>
                        <div class="info">{{ $storeData->address }}</div>
                    </td>
                </tr>
            </table>
            <main class="table-responsive">
                {!! Form::open(array('url' => 'supplier/createbluckinvoice/'.$storeData->id,'id' => 'flag_submit','files'=>true,'method'=>'POST')) !!}
                @foreach ($main_order_ids as $order_id)
                <input type="hidden" class="flag_checkbox" name="flag[]" data-id="flagData" value="{{$order_id}}" />
                @endforeach
                <table cellpadding="0" cellspacing="0" border-color="#000" class="listdata table-bordered " style="width:100%;height:380px; margin-top: 25px;font-size: 16px;">    
                    <tr align="left">
                        <th style=" width:30px; text-align: center;">Item</th>
                        <th style="width:400px; text-align: center;">Name</th>
                        <th style="width:100px; text-align: center;">Price</th>
                        <th style="width:120px; text-align: center;">Quantity</th>
                        <th style=" text-align: center; width: 70px;">Amount</th>
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
                            <td class="amount" style="">{{ number_format(($oVal['product_admin_price'] * $oVal['product_quantity']), 2) }}</td>
                        </tr>
                        <?php
                        $main_invoice_total += ($oVal['product_admin_price'] * $oVal['product_quantity']);
                    }
                    ?>
                    <tr valign="top">
                        <td colspan="3" style="">

                        </td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <!-- <div style="width:50px; height:3px; position: absolute;top:136px; right:-10px; color: #e7e8e9; background: #ff7900"></div> -->
                            <p style="font-size:16px;font-weight: 600;">SUB TOTAL</p>
                        </td>
                        <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                            <p style="font-size:16px !important; color: #ff7900 "><b>{{number_format($main_invoice_total,2)}}</b></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td colspan="3" style="padding:10px;">
                            {!! Form::text('other_charges_description', NULL, array('placeholder' => 'Enter other payment description here...', 'class' => 'form-control')) !!}     
                        </td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <p style="font-size:16px;font-weight: 600;">OTHER</p>
                        </td>
                        <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                            <p style="font-size:16px !important; color: #ff7900 "><b>{!! Form::number('other_charges', '0', array('step' => 'any', 'min' => '1', 'class' => 'form-control other_charges_box')) !!}</b></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td colspan="3" style="">

                        </td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <p style="font-size:16px;font-weight: 600;">TOTAL</p>
                        </td>
                        <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                            <p style="font-size:16px !important; color: #ff7900"><b><span class="invoice_total_box">{{number_format($main_invoice_total,2)}}</span></b></p>
                        </td>
                    </tr>
                </table>
                <br>
                @if(count($main_order_ids) > 0 && $main_invoice_total > 0)
                <input type="submit" class="btn btn-danger create_invoice_btn" name="create_invoice" value="Create Invoice" />
                @endif
                {!! Form::close() !!}
            </main>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function () {
        //metod to do sum of all tax and other prices
        var subTotalVal = "{{ $main_invoice_total }}";
        $('.other_charges_box').on('keyup', function () {
            var otherChargesBox = $('.other_charges_box').val();
            var totalVal = parseFloat(subTotalVal) + parseFloat(otherChargesBox);
            $('.invoice_total_box').text(totalVal.toFixed(2));
        });

        //metod call on create invoice button
        $('.create_invoice_btn').on('click', function () {
            if (confirm('Please confirm, really you want to create an invoice!')) {
                $("#flag_submit").submit();
            }
        });
    });
</script>
@endsection