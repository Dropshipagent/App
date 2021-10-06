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
        padding-right: 10px;
        font-size: 18px;
    }

    td.campanyinfo.customInvoice-companyinfo {
        padding-bottom: 15px;
    }

    .table-responsive {
        padding: 13px;
    }

    .listdata td,
    .listdata th {
        padding: 10px;
    }

    .listdata th.action {
        width: 8px;
    }
</style>
<section class="content">
    <div class="nav-tabs-custom invoiceDetails" style="width: 1000px; margin: auto; font-size: 16px; padding:25px;">
        <div class="tab-content table-responsive">

            <form method="POST" action="/supplier/custominvoice">
                @csrf

                <table cellpadding="0" cellspacing="5" border="0" style="width:100% !important; margin-top: 25px;">

                    <tr>
                        <td colspan="2" class="text-center header_txtt">
                            <h1 class="heading color-white"><span class="color_ye">DROPSHIP</span>&nbsp; AGENT <span class="sub_heads"><i>CUSTOM INVOICES</i></span></h1>
                        </td>
                    </tr>

                    <tr class="topheading">
                        <td valign="top" style="width:150px">
                            <!-- <div class="invoice_logo">
                            <img src="{{url('admin/dist/img/logo.png')}}" >  
                        </div> -->
                        </td>
                        <td align="right" class="campanyinfo customInvoice-companyinfo">
                            <div class="head"><strong>ORDER ID: </strong><input type="text" name="orderid" id="orderid" style="color:#000;"></div>
                            <div class="head"><strong>INVOICE: </strong><input type="text" name="store_domain" readonly="readonly" id="invoice" style="color:#000;" placeholder="INVOICE" value="<?php echo $storeuser->username; ?>"></div>
                            <div class="info"><strong>INVOICE FOR: </strong><input type="text" readonly="readonly" name="invoicefor" value="<?php echo $storeuser->address; ?>" id="invoicefor" style="color:#000;" placeholder="INVOICE FOR"></div>
                            <!-- <div class="info"><input type="text" name="address" id="address" style="color:#000;" placeholder="ADDRESS"></div> -->

                        </td>
                    </tr>

                </table>

                <main class="table-responsive">

                    <input type="hidden" class="flag_checkbox" name="flag[]" data-id="flagData" value="1" />

                    <div class="text-right">
                        <input type="button" class="btn btn-danger" id="addmorePOIbutton" value="Add Item" onclick="insRow()" />
                    </div>


                    <table id="POITable" cellpadding="0" cellspacing="0" border-color="#000" class="listdata table-bordered " style="width:100%;height:210px; margin-top: 25px;font-size: 16px;">

                        <thead>

                            <tr align="left">
                                <th style="width:300px; text-align: center;">Name</th>
                                <th style="width:150px; text-align: center;">Price</th>
                                <th style="width:170px; text-align: center;">Quantity</th>
                                <th style=" text-align: center; width: 70px;">Amount</th>
                                <th class="action">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            <tr valign="center">

                                <td class="description"><input type="text" name="items[item_name][]" id="item_name" class="product_name" placeholder="Product Name" style="color:#000; width:100px;"></td>

                                <td class="price text-center">
                                    <input type="number" step="any" name="items[item_price][]" id="price" class="price" placeholder="Price" value="" style="color:#000; width:100px;">
                                </td>

                                <td class="quantity text-center">
                                    <input type="number" name="items[item_qty][]" step="any" min="1" id="quantity" class="quantity" placeholder="Qty" value="1" style="color:#000; width:60px;">
                                    <input type="hidden" name="items[admin_commission][]" step="any" min="1" value="">
                                </td>

                                <td class="amount" style="" id="productAmount">
                                    <span class="newPrice">
                                        0.00
                                    </span>
                                </td>

                                <td><input type="button" class="btn btn-danger itemdelete" id="delPOIbutton" value="Delete" onclick="deleteRow(this)" /></td>

                            </tr>
                        </tbody>
                        <tfoot>

                            <tr valign="top">
                                <td colspan="1" style="">

                                </td>

                                <td colspan="2" style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                                    <p style="font-size:16px;font-weight: 600;">SUB TOTAL</p>
                                </td>
                                <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                                    <p style="font-size:16px !important; color: #ff7900 " class="subTotal"><b>0.00</b></p>
                                </td>
                            </tr>

                            <tr valign="top">
                                <td colspan="1" style="padding:10px;">
                                    <input placeholder="Enter other payment description here..." class="form-control" name="other_charges_description" type="text">
                                </td>
                                <td colspan="2" style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                                    <p style="font-size:16px;font-weight: 600;">OTHER</p>
                                </td>
                                <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                                    <p style="font-size:16px !important; color: #ff7900 "><b><input step="any" min="0" class="form-control other_charges_box" name="other_charges" type="number" value="1"></b></p>
                                </td>
                            </tr>

                            <tr valign="top">
                                <td colspan="1" style="">

                                </td>
                                <td colspan="2" style="position: relative; padding-top: 10px; padding-right: 15px; " align="right" class="netamount">
                                    <p style="font-size:16px;font-weight: 600;">TOTAL</p>
                                    <input type="hidden" name="admin_total_commission" id="admin_total_commission">
                                    <input type="hidden" name="invoice_total" id="invoice_total" class="invoice_total_box">
                                </td>
                                <td style=" text-align: right; font-size: 18px;padding-top: 10px; padding-right: 15px;">
                                    <p style="font-size:16px !important; color: #ff7900"><b><span class="invoice_total_box">0.00</span></b></p>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
                    <input type="submit" class="btn btn-danger create_invoice_btn" name="create_invoice" value="Create Invoice" />
                </main>
            </form>
        </div>
    </div>
</section>
<script>
    function deleteRow(row) {
        var checkrows = jQuery('#POITable').find('tbody').find('tr').length;
        console.log(checkrows);
        if (checkrows == 2) {
            jQuery('.itemdelete').hide();
        }
        if (checkrows > 1) {
            var i = row.parentNode.parentNode.rowIndex;
            document.getElementById('POITable').deleteRow(i);
        }
    }

    function insRow() {
        jQuery('.itemdelete').show();
        var x = document.getElementById('POITable');
        // deep clone the targeted row
        var new_row = x.rows[1].cloneNode(true);
        // get the total number of rows
        var len = x.rows.length;
        // set the innerHTML of the first row 
        //new_row.cells[0].innerHTML = len;

        // grab the input from the first cell and update its ID and value
        var inp0 = new_row.cells[0].getElementsByTagName('input')[0];
        inp0.id += len;
        inp0.value = '';

        // grab the input from the first cell and update its ID and value
        var inp1 = new_row.cells[1].getElementsByTagName('input')[0];
        inp1.id += len;
        inp1.value = '';

        // grab the input from the first cell and update its ID and value
        var inp2 = new_row.cells[2].getElementsByTagName('input')[0];
        inp2.id += len;
        inp2.value = 1;

        // append the new row to the table
        jQuery('#POITable').find('tbody').append(new_row);
    }

    $(document).ready(function() {
        $(document).on("keyup", ".price", function() {
            changePrice();
        });
        $(document).on("keyup", ".quantity", function() {
            changePrice();
        });
        $(document).on("keyup", ".other_charges_box", function() {
            changePrice();
        });
        $(document).on("click", "#delPOIbutton", function() {
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