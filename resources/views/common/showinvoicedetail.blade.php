<?php $layout = 'layouts.app'; if(auth()->user()->role == 1) { $layout = 'admin.layouts.app';  } if(auth()->user()->role == 3) { $layout = 'supplier.layouts.app';  } ?>
@extends($layout)
@section('title', 'Invoice Detail')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">
    @if(auth()->user()->role == 1)
    <a href="{{ url('admin/showinvoiceslog/') }}" class="btn btn-success" title="View Invoice List"><i class="fa fa-arrow-left"></i> Back</a>
    @endif
    @if(auth()->user()->role == 2)
    <a href="{{ url('showinvoiceslog') }}" class="btn btn-success" title="View Invoice List"><i class="fa fa-arrow-left"></i> Back</a>
    @endif
    @if(auth()->user()->role == 3)
    <a href="{{ url('supplier/showinvoiceslog/'.Session::get('selected_store_id')) }}" class="btn btn-success" title="View Invoice List"><i class="fa fa-arrow-left"></i> Back</a>
    @endif
</section>
<!-- Main content -->
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
                <tr>
                    <td colspan="2" class="text-center header_txtt"> <h1><span class="color_ye">DROPSHIP</span>AGENT<span class="sub_heads"><i>INVOICES</i></span></h1></td>
                </tr>  
                <tr class="topheading">
                    <td valign="top" style="width:150px">
                        <!-- <div class="invoice_logo">
                            <img src="{{url('admin/dist/img/logo.png')}}" >  
                        </div> -->                        
                    </td>
                    <td align="right" class="campanyinfo">
                        <div class="invoice_date"><strong>DATE: </strong><span>{{ date('M d, Y H:i:s', strtotime($mainInvoice->created_at)) }}</span></div>
                        <div class="head"><strong>INVOICE: </strong>{{ $storeData->username }}</div>
                        <div class="info"><strong>INVOICE FOR: </strong>{{ $storeData->email }}</div>
                        <div class="info">{{ $storeData->address }}</div>
                    </td>
                </tr>
            </table>
            <main>                
                <table cellpadding="0" cellspacing="0" border-color="#000" class="listdata table-bordered " style="width:100%;height:380px; margin-top: 25px;font-size: 16px;">    
                    <tr align="left">
                        <th style=" width:30px; text-align: center;">Item</th>
                        <th style="width:400px; text-align: center;">Name</th>
                        <th style="width:100px; text-align: center;">Price</th>
                        <th style="width:120px; text-align: center;">Quantity</th>
                        <th style="background: #e7e8e9; text-align: center; width: 70px;">Amount</th>
                    </tr>
                    <?php
                    $main_invoice_total = 0;
                    if (auth()->user()->role == 3) {
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
                    } else {
                        foreach ($invoice_items as $oKey => $oVal) {
                            $price_by_admin = ($oVal['product_admin_price'] / $oVal['product_quantity']);
                            $admin_commission = ($oVal['product_admin_commission'] / $oVal['product_quantity']);
                            $product_price = ($price_by_admin + $admin_commission);
                            ?>
                            <tr valign="center">
                                <td class="items">{{ $oKey }}</td>
                                <td class="description">{{ $oVal['product_title'] }}</td>
                                <td class="price text-right" style="padding-right: 10px;">{{ $product_price }}</td>
                                <td class="quantity text-right" style="padding-right: 10px;"> {{ $oVal['product_quantity'] }}</td>
                                <td class="amount" style="background: #e7e8e9;">{{ number_format(($product_price*$oVal['product_quantity']), 2) }}</td>
                            </tr>
                            <?php
                            $main_invoice_total += ($product_price * $oVal['product_quantity']);
                        }
                    }
                    ?>
                    <tr valign="top">
                        <td colspan="3" style="">

                        </td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <!-- <div style="width:50px; height:3px; position: absolute;top:136px; right:-10px; color: #e7e8e9; background: #ff7900"></div> -->
                            <p style="font-size:16px; color:#000;font-weight: 600;">SUB TOTAL</p>
                        </td>
                        <td style="background: #e7e8e9; text-align: right; font-size: 18px; color:#000;padding-top: 10px; padding-right: 15px;">
                            <p style="font-size:16px !important; color: #ff7900 "><b>{{number_format($main_invoice_total,2)}}</b></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td colspan="3" style="padding:10px;">
                            {{ $mainInvoice->other_charges_description }}
                        </td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <p style="font-size:16px; color:#000;font-weight: 600;">OTHER</p>
                        </td>
                        <td style="background: #e7e8e9; text-align: right; font-size: 18px; color:#000;padding-top: 10px; padding-right: 15px;">
                            <p style="font-size:16px !important; color: #ff7900 "><b>{{number_format($mainInvoice->other_charges,2)}}</b></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td colspan="3" style="">

                        </td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <p style="font-size:16px; color:#000;font-weight: 600;">TOTAL</p>
                        </td>
                        <td style="background: #e7e8e9; text-align: right; font-size: 18px; color:#000;padding-top: 10px; padding-right: 15px;">
                            <p style="font-size:16px !important; color: #ff7900 "><b>{{number_format(($main_invoice_total+$mainInvoice->tax_rate+$mainInvoice->sales_tax+$mainInvoice->other_charges),2)}}</b></p>
                        </td>
                    </tr>
                </table>
                <br>

                <p class="text-center text-uppercase" style="font-size: 16px; font-weight: 600;">Thank You for your business</p>
                @if($mainInvoice->paid_status < 1 && auth()->user()->role == 2)
                <button class="pay-invoice pay_now_btn" data-id="{{$mainInvoice->id}}" data-val="{{number_format(($main_invoice_total+$mainInvoice->other_charges),2)}}">PAY INVOICE</button>
                @endif

            </main>
        </div>
    </div>
</section>

<!-- Modal - this model will be shown only in the store owner case -->
@if(auth()->user()->role == 2)
<div id="payNowModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Pay Now</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="content">
                        <form class="" action="{{ url('/invoice_checkout') }}" method="post">
                            {{ csrf_field() }}
                            {!! Form::hidden('invoiceID', null, array('class' => 'invoice_id')) !!}
                            {!! Form::hidden('camount', null, array('class' => 'invoice_amount')) !!}
                            @if(count($userCardProfiles)>0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Card</th>
                                        <th>Ending With</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userCardProfiles as $key => $val)
                                    <tr>
                                        <td><label for="card_type{{ $val['item_profile_id'] }}">{!! Form::radio('payment_option', $val['item_profile_id'], false, ['class' => 'select_card', 'id' => 'card_type'.$val['item_profile_id']]) !!} </label></td>
                                        <td>{{ $val['card_type'] }}</td>
                                        <td>{{ $val['card_4_digit'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                            <label for="custom_card">{!! Form::checkbox('card_option', 'custom_card', false, ['class' => 'custom_card', 'id' => 'custom_card']) !!} Add new card</label><br/><br/>
                            <div class="addNewCard" {{ (count($userCardProfiles) > 0)?"style=display:none;":"" }}>
                                <h3>Credit Card Information</h3>
                                <div class="form-group">
                                    <label for="cnumber">Card Number</label>
                                    <input type="text" class="form-control cnumber" id="cnumber" name="cnumber" placeholder="Enter Card Number">
                                </div>
                                <div class="form-group">
                                    <label for="card-expiry-month">Expiration Month</label>
                                    {{ Form::selectMonth(null, null, ['name' => 'card_expiry_month', 'class' => 'form-control card_expiry_month']) }}
                                </div>
                                <div class="form-group">
                                    <label for="card-expiry-year">Expiration Year</label>
                                    {{ Form::selectYear(null, date('Y'), date('Y') + 10, null, ['name' => 'card_expiry_year', 'class' => 'form-control card_expiry_year']) }}
                                </div>
                                <div class="form-group">
                                    <label for="ccode">Card Code</label>
                                    <input type="text" class="form-control ccode" id="ccode" name="ccode" placeholder="Enter Card Code">
                                </div>
                            </div>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- (c) 2005, 2020. Authorize.Net is a registered trademark of CyberSource Corporation --> <div style="margin-left: 30px; clear: both;" class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id = "f3bc89f6-aaf8-4c87-b3c6-65b911c74055";</script> <script type="text/javascript" language="javascript" src="https://verify.authorize.net/anetseal/seal.js" ></script> </div>
            </div>
        </div>

    </div>
</div>
@endif
<script type="text/javascript">
                    $(document).ready(function () {
                        //show modal popup jquery
                        $(document).on('click', '.pay_now_btn', function (e) {
                            $('.invoice_id').val($(this).data("id"));
                            $('.invoice_amount').val($(this).data("val"));
                            // show Modal
                            $('#payNowModal').modal('show');
                        });

                        jQuery('.select_card').change(function () {
                            if (jQuery('.custom_card').is(':checked')) {
                                jQuery('.custom_card').trigger("click");
                            }
                        });
                        jQuery('.custom_card').change(function () {
                            if (jQuery(this).is(':checked')) {
                                jQuery(".select_card").each(function () {
                                    jQuery(this).prop("checked", false);
                                });
                                jQuery('.cnumber').prop("required", true);
                                jQuery('.card_expiry_month').prop("required", true);
                                jQuery('.card_expiry_year').prop("required", true);
                                jQuery('.ccode').prop("required", true);
                                jQuery('.addNewCard').show();
                            } else {
                                jQuery('.cnumber').prop("required", false);
                                jQuery('.card_expiry_month').prop("required", false);
                                jQuery('.card_expiry_year').prop("required", false);
                                jQuery('.ccode').prop("required", false);
                                jQuery('.addNewCard').hide();
                            }
                        });

                    });
</script>
@endsection