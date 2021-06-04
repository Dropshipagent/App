<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' 
              name='viewport'>
        <title>Invoice Report</title>
        <style>
            table {
                border-collapse: collapse;
                border-spacing: 0;
            }

            .heading td{
                padding: 10px 5px;
                font-size: 17px;
            }

            td,th{
                padding: 15px 5px;
                font-size: 16px;
            }
            .price, .quantity{
                text-align:center;
            }
            .color_ye {color: #f7d104;}
            span.sub_heads {font-size: 36px;display: block;}
            .header_txtt h1 {font-size: 50px;text-align: center;margin:0;color:#FFF;}
            .table-bordered, .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {border:1px solid #000;}
            button.pay-invoice {
                background-color: #49c019;
                border: 1px solid #49c019;
                color: #FFF;
                padding: 22px 60px;
                font-size: 40px;
                border-radius: 10px;
                margin: 30px auto;
                display: block;
                font-style: italic;
                font-weight: 600;
            } 
            p {margin:0;}
            body, p{font-family: 'Montserrat', sans-serif;}
            body {background-color: #2a2a2a;color: #FFF;}
            @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;900&display=swap');
            tr.heading {
    background-color: #000;
}
tr.heading>th {color: #000;text-align: center;background-color: #f7d104;}
.listdata tr:nth-child(even) {background-color: #232323;}
.listdata td {text-align: center;}
.listdata tr:nth-child(even) td:last-child {
    border-radius: 0px 100px 100px 0;
    overflow: hidden;
}
.listdata tr td {padding: 15px 0 !important;}
.listdata tr, .listdata tr td {height:50px;padding: 0;}
@page {
        margin: 0mm 0mm 0mm 0mm;
    }
        </style>
    </head>
    <body>
        <table cellpadding="0" cellspacing="5" border="0" style="width:90% !important; margin: 25px auto 0;">    
            <tr>
                <td colspan="2" class="text-center header_txtt"> <h1><span class="color_ye">DROPSHIP</span>AGENT<span class="sub_heads"><i>INVOICES</i></span></h1></td>
            </tr> 
            <tr class="topheading">
                <td  style="width:150px;">
                    <!-- <div style="padding:15px; background-color:#367fa9"><img src="{{url('admin/dist/img/logo.png')}}" > </div>  -->
                </td>
                <td align="right" class="campanyinfo">
                    <div class="head">{{ $storeData->username }}</div>
                    <div class="info">{{ $storeData->email }}</div>
                    <div class="info">{{ $storeData->address }}</div>

                </td>
            </tr>
        </table>               
        <table cellpadding="0" cellspacing="0" border-color="#000" class="listdata table-bordered" style="width:90%; margin-top: 25px; margin-left: auto; margin-right: auto; max-height:400px; min-height: 400px; background-color: ;">  
        <thead>  
            <tr align="left" class="heading">
                <th style="border:1px solid #000; border-right:none; border-left:none;  width:120px;">Item</th>
                <th style="border:1px solid #000; border-right:none; width:250px; padding-left: 15px; ">Name</th>
                <th style="border:1px solid #000; border-right:none; width:60px; text-align: center;">Price</th>
                <th style="border:1px solid #000; border-right:none; width:100px; text-align: center;">Quantity</th>
                <th style="border:none; border-top:1px solid #000;border-left:1px solid #000;text-align: center; width: 100px;padding-left: 15px;">Amount</th>
            </tr>
        </thead>
        <tbody>
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
                        <td class="amount">{{ number_format(($oVal['product_admin_price']), 2) }}</td>
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
                        <td class="amount">{{ number_format(($product_price*$oVal['product_quantity']), 2) }}</td>
                    </tr>
                    <?php
                    $main_invoice_total += ($product_price * $oVal['product_quantity']);
                }
            }
            ?>
            <tr >
                <td colspan="3" style="">

                </td>
                <td style="position: relative; padding-top: 15px; padding-right: 15px; font-size: 16px; " align="right" class="netamount">SUB TOTAL
                </td>
                <td style=" font-size: 22px;padding-top: 15px; padding-right: 15px;">
                    <b>{{number_format($main_invoice_total,2)}}</b>
                </td>
            </tr>
            <tr >
                <td colspan="3" style="padding:10px;">
                    {{ $mainInvoice->other_charges_description }}
                </td>
                <td style="position: relative; padding-top: 15px; padding-right: 15px;font-size: 16px; " align="right" class="netamount">
                    OTHER
                </td>
                <td style=" font-size: 18px;padding-top: 15px; padding-right: 15px;color: #ff7900;">
                   {{number_format($mainInvoice->other_charges,2)}}
                </td>
            </tr>

            <tr >
                <td colspan="3" style="">

                </td>
                <td style="position: relative; padding-top: 15px; padding-right: 15px; font-size: 18px; " align="right" class="netamount">
                    TOTAL
                </td>
                <td style=" font-size: 22px;padding-top: 15px; padding-right: 15px;">
                    {{number_format(($main_invoice_total+$mainInvoice->other_charges),2)}}
                </td>
            </tr>
        </tbody>

        </table>
        <br>

        <p style="font-size: 16px; font-weight: 600;text-align: center;text-transform: uppercase;font-family: 'Montserrat', sans-serif;">Thank You for your business</p>
    </body>
</html>

