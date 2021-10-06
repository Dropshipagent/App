
<?php
$layout = 'layouts.app';
if (auth()->user()->role == 1) {
    $layout = 'admin.layouts.app';
} if (auth()->user()->role == 3) {
    $layout = 'supplier.layouts.app';
}
?>
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
    .amount{ text-align: right; padding-right: 15px; font-size: 18px;}
</style>
<section class="content">
    <div class="nav-tabs-custom invoiceDetails" style="width: 1000px; margin: auto; font-size: 16px; padding:25px;">
        <div class="tab-content table-responsive" >

            <table cellpadding="0" cellspacing="5" border="0" style="width:100% !important; margin-top: 25px;">  
                <tr>
                    <td colspan="2" class="text-center header_txtt"> <h1 class="heading color-white"><span class="color_ye">DROPSHIP</span>AGENT<span class="sub_heads"><i>INVOICES</i></span></h1></td>
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
            <main class="table-responsive">                
                <table cellpadding="0" cellspacing="0" border-color="#000" class="listdata table-bordered " style="width:100%;height:380px; margin-top: 25px;font-size: 16px;">    
                    <tr align="left">
                        <!-- <th style=" width:30px; text-align: center;">Item</th> -->
                        <th style="width:400px; text-align: center;">Name</th>
                        <th style="width:100px; text-align: center;">Price</th>
                        <th style="width:120px; text-align: center;">Quantity</th>
                        <th style=" text-align: center; width: 70px;">Amount</th>
                    </tr>
                    <?php
                    $main_invoice_total = 0;    
                    if (auth()->user()->role == 1) {
                        $invoice_items = json_decode($invoice->invoice_items, true) ;
                        $invoiceSubTotal= 0;
                        foreach ($invoice_items['item_name'] as $oKey => $oVal) {
                            $item_price = ($invoice_items['item_price'][$oKey]+$invoice_items['admin_commission'][$oKey]);
                            $main_invoice_total+= number_format(($item_price*$invoice_items['item_qty'][$oKey]), 2) ;  
                        ?>
                        <tr valign="center">
                            <td class="description">{{ $oVal }}</td>
                            <td class="price">{{ $item_price }}</td>
                            <td class="quantity"> {{ $invoice_items['item_qty'][$oKey] }}</td>
                            <td class="amount">{{ number_format(($item_price*$invoice_items['item_qty'][$oKey]), 2) }}</td>
                        </tr>
                        <?php
                        }
                    } else if (auth()->user()->role == 3) {
                        $invoice_items = json_decode($invoice->invoice_items, true) ;
                        $invoiceSubTotal= 0;
                        foreach ($invoice_items['item_name'] as $oKey => $oVal) {
                        $main_invoice_total+= number_format(($invoice_items['item_price'][$oKey]*$invoice_items['item_qty'][$oKey]), 2) ;  
                        ?>
                        <tr valign="center">
                            <td class="description">{{ $oVal }}</td>
                            <td class="price">{{ ($invoice_items['item_price'][$oKey]) }}</td>
                            <td class="quantity"> {{ $invoice_items['item_qty'][$oKey] }}</td>
                            <td class="amount">{{ number_format(($invoice_items['item_price'][$oKey]*$invoice_items['item_qty'][$oKey]), 2) }}</td>
                        </tr>
                        <?php
                        }
                    } else {
                        $invoice_items = json_decode($invoice->invoice_items, true) ;
                        $invoiceSubTotal= 0;
                        foreach ($invoice_items['item_name'] as $oKey => $oVal) {
                        $main_invoice_total+= number_format(($invoice_items['item_price'][$oKey]*$invoice_items['item_qty'][$oKey]), 2) ;  
                        ?>
                        <tr valign="center">
                            <td class="description">{{ $oVal }}</td>
                            <td class="price">{{ ($invoice_items['item_price'][$oKey]) }}</td>
                            <td class="quantity"> {{ $invoice_items['item_qty'][$oKey] }}</td>
                            <td class="amount">{{ number_format(($invoice_items['item_price'][$oKey]*$invoice_items['item_qty'][$oKey]), 2) }}</td>
                        </tr>
                        <?php
                        }
                    }
                    ?>
                    <tr valign="top">
                        <td colspan="2"></td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <p style="font-size:16px;font-weight: 600;">SUB TOTAL</p>
                        </td>
                        <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                            <p style="font-size:16px !important; color: #ff7900 ">
                                 <b>{{ $main_invoice_total }}</b>
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td colspan="2" style="padding:10px;">
                            {{ $mainInvoice->other_charges_description }}
                        </td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <p style="font-size:16px;font-weight: 600;">OTHER</p>
                        </td>
                        <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                            <p style="font-size:16px !important; color: #ff7900 "><b>{{number_format($invoice->other_charges,2)}}</b></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td colspan="2"></td>
                        <td style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                            <p style="font-size:16px;font-weight: 600;">TOTAL</p>
                        </td>
                        <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                            <p style="font-size:16px !important; color: #ff7900 "><b>{{ number_format(($main_invoice_total+$invoice->other_charges),2) }}
                            </b></p>
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
<script type="text/javascript">
    $(document).ready(function () {
        //show modal popup jquery
        $(document).on('click', '.pay_now_btn', function (e) {
            var invoiceID = $(this).data("id");
            var invoiceAmount = $(this).data("val");
            $.ajax({
                url: '{{ url("payment-info-page") }}',
                type: "GET",
                dataType: "html",
                data: {"invoice_id": invoiceID, "invoice_amount": invoiceAmount},
                success: function (data) {
                    showAlertMessage(data, "Pay Now");
                },
                error: function (xhr, status) {
                    alert("Sorry, there was a problem!");
                },
                complete: function (xhr, status) {
                    $('#acceptProductsModal').modal('show');
                }
            });
        });
    });
</script>
@endsection