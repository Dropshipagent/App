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

    .amount {
        text-align: right;
        padding-right: 15px;
        font-size: 18px;
    }
</style>
<section class="content">
    <div class="nav-tabs-custom invoiceDetails" style="width: 1000px; margin: auto; font-size: 16px; padding:25px;">
        <div class="tab-content table-responsive">

            <table cellpadding="0" cellspacing="5" border="0" style="width:100% !important; margin-top: 25px;">
                <tr>
                    <td colspan="2" class="text-center header_txtt">
                        <h1 class="heading color-white"><span class="color_ye">DROPSHIP</span>&nbsp; AGENT <span class="sub_heads"><i>INVOICES</i></span></h1>
                    </td>
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
                        <th style="width:400px; text-align: center;">Name</th>
                        <th style="width:100px; text-align: center;">Price</th>
                        <th style="width:120px; text-align: center;">Quantity</th>
                        <th style=" text-align: center; width: 70px;">Amount</th>
                    </tr>
                    <?php
                    $main_invoice_total = 0;
                    foreach ($invoice_items as $oKey => $oVal) {
                        //dd($oVal);
                    ?>
                        <tr valign="center">

                            <td class="description">{{ $oVal['product_title'] }} <input type="hidden" name="items[item_name][]" value="{{ $oVal['product_title'] }}"></td>
                            <td class="price text-center">
                                <input type="number" name="items[item_price][]" step="any" id="price" class="price" min="0" value="{{ $oVal['product_admin_price'] }}" style="color:#000; width:100px;">
                            </td>
                            <td class="quantity text-center">
                                <input type="hidden" name="items[admin_commission][]" step="any" min="1" value="">
                                <input type="number" min="1" name="items[item_qty][]" id="quantity" class="quantity" value="{{ $oVal['product_quantity'] }}" style="color:#000; width:60px;">
                            </td>
                            <td class="amount" style="" id="productAmount">
                                <span class="newPrice">
                                    {{ number_format(($oVal['product_admin_price'] * $oVal['product_quantity']), 2) }}
                                </span>
                            </td>
                        </tr>
                    <?php
                        $main_invoice_total += ($oVal['product_admin_price'] * $oVal['product_quantity']);
                    }
                    ?>
                    <tr valign="top">
                        <td colspan="2" style="">

                        </td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <!-- <div style="width:50px; height:3px; position: absolute;top:136px; right:-10px; color: #e7e8e9; background: #ff7900"></div> -->
                            <p style="font-size:16px;font-weight: 600;">SUB TOTAL</p>
                        </td>
                        <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                            <p style="font-size:16px !important; color: #ff7900 " class="subTotal"><b>{{number_format($main_invoice_total,2)}}</b>

                                <!-- <input type="text" name="subtotal" id="subtotal" value="{{number_format($main_invoice_total,2)}}"> -->
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td colspan="2" style="padding:10px;">
                            {!! Form::text('other_charges_description', NULL, array('placeholder' => 'Enter other payment description here...', 'class' => 'form-control')) !!}
                        </td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <p style="font-size:16px;font-weight: 600;">OTHER</p>
                        </td>
                        <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                            <p style="font-size:16px !important; color: #ff7900 "><b>{!! Form::number('other_charges', '1', array('step' => 'any', 'min' => '1', 'class' => 'form-control other_charges_box')) !!}</b></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td colspan="2" style="">

                        </td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <p style="font-size:16px;font-weight: 600;">TOTAL</p>
                        </td>
                        <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                            <input type="hidden" name="invoice_total" id="invoice_total">
                            <input type="hidden" name="admin_total_commission" id="admin_total_commission">
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
    $(document).ready(function() {
        //metod to do sum of all tax and other prices
        var subTotalVal = "{{ $main_invoice_total }}";
        // $('.other_charges_box').on('keyup', function () {
        //     var otherChargesBox = $('.other_charges_box').val();
        //     var totalVal = parseFloat(subTotalVal) + parseFloat(otherChargesBox);
        //     $('.invoice_total_box').text(totalVal.toFixed(2));
        // });

        //metod call on create invoice button
        $('.create_invoice_btn').on('click', function() {
            if (confirm('Please confirm, really you want to create an invoice!')) {
                $("#flag_submit").submit();
            }
        });

        $('.price').on('keyup', function() {
            changePrice();
        });
        $('.quantity').on('keyup', function() {
            changePrice();
        });
        $('.other_charges_box').on('keyup', function() {
            changePrice();
        });

        function changePrice() {
            var total = 0;
            var admin_commission = 1;
            var admin_total_commission = 0;
            $('.price').each(function() {
                var selfval = $(this).val();
                if (jQuery.trim(selfval) != '') {
                    var qty = $(this).parents('tr').find('input[name="items[item_qty][]"]').val();
                    if (jQuery.trim(qty) != '') {
                        var subtotal = parseFloat(selfval) * parseFloat(qty);
                        $(this).parents('tr').find('.newPrice').text(subtotal.toFixed(2));
                        total += parseFloat(selfval) * parseFloat(qty);
                    } else {
                        var subtotal = parseFloat(selfval);
                        $(this).parents('tr').find('.newPrice').text(subtotal.toFixed(2));
                        total += parseFloat(selfval);
                    }
                    admin_total_commission += admin_commission * parseFloat(qty);
                    $(this).parents('tr').find('input[name="items[admin_commission][]"]').val((admin_commission * parseFloat(qty)));

                } else {
                    selfval = 0;
                    $(this).parents('tr').find('.newPrice').text(selfval.toFixed(2));
                }
            });          

            $('.subTotal').text(total.toFixed(2));
            $('#invoice_total').val(total.toFixed(2));
            $('#admin_total_commission').val(parseFloat(admin_total_commission));
            var otherChargesBox = $('.other_charges_box').val();
            if (jQuery.trim(otherChargesBox) != '') {
                var newtotal = parseFloat(total) + parseFloat(otherChargesBox);
                $('.invoice_total_box').text(newtotal.toFixed(2));
            } else {
                $('.invoice_total_box').text(total.toFixed(2));

            }
        }
    });
</script>
@endsection