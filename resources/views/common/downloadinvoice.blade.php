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
                font-family:'Roboto-Regular';
            }

            .heading td{
                padding: 10px 5px;
                font-family: 'Roboto-Regular';
                font-size: 17px;
            }

            td,th{
                padding: 15px 5px;
                font-family: 'Roboto-Regular';
                font-size: 16px;
            }
            .price, .quantity{
                text-align:center;
                font-family: 'Roboto-Regular';
            }
            .amount{
                text-align:right;
                font-family: 'Roboto-Regular';
            }
            .color_ye {color: #f7d104;}
            span.sub_heads {font-size: 36px;display: block;}
            .header_txtt h1 {font-size: 50px;text-align: center;margin:0;}
            .table-bordered, .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {border:1px solid #000;}
            .table-bordered>tbody>tr:nth-child(odd)>td {background-color: #e7e8e9;}
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

            body{
                font-family: 'Roboto-Regular';
            }
            @font-face {
                font-family: 'Roboto-Regular';
                font-style: light;
                font-weight: normal;
                src: url({{ storage_path('fonts/Roboto-Regular.ttf') }}) format('truetype');
            }
        </style>
    </head>
    <body>
        <table cellpadding="0" cellspacing="5" border="0" style="width:100% !important; margin-top: 25px;">    
            <tr>
                <td colspan="2" class="text-center header_txtt"> <h1><span class="color_ye">DROPSHIP</span>AGENT<span class="sub_heads"><i>INVOICES</i></span></h1></td>
            </tr> 
            <tr class="topheading">
                <td valign="top" style="width:150px;">
                    <!-- <div style="padding:15px; background-color:#367fa9"><img src="{{url('admin/dist/img/logo.png')}}" > </div>  -->
                </td>
                <td align="right" class="campanyinfo">
                    <div class="head">{{ $storeData->username }}</div>
                    <div class="info">{{ $storeData->email }}</div>
                    <div class="info">{{ $storeData->address }}</div>

                </td>
            </tr>
        </table>               
        <table cellpadding="0" cellspacing="0" border-color="#000" class="listdata table-bordered" style="width:90%; margin-top: 25px; margin-left: auto; margin-right: auto; max-height:400px; min-height: 400px;">    
            <tr align="left" class="heading">
                <td style="border:1px solid #000; border-right:none; border-left:none;  width:120px; height:30px; font-family: 'Roboto-Regular';">Item</td>
                <td style="border:1px solid #000; border-right:none; width:250px; padding-left: 15px; ">Name</td>
                <td style="border:1px solid #000; border-right:none; width:60px; text-align: center;">Price</td>
                <td style="border:1px solid #000; border-right:none; width:100px; text-align: center;">Quantity</td>
                <td style="border:none; border-top:1px solid #000; background: #e7e8e9; text-align: center; width: 100px;padding-left: 15px;">Amount</td>
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
                <td style="position: relative; padding-top: 15px; padding-right: 15px; " align="right" class="netamount">
                    <p style="font-size:16px; color:#000;">SUB TOTAL</p>
                </td>
                <td style="background: #e7e8e9; text-align: right; font-size: 18px; color:#000;padding-top: 15px; padding-right: 15px;">
                    <p style="font-size:22px !important; color: #ff7900; font-family: 'Roboto-Regular'; "><b>{{number_format($main_invoice_total,2)}}</b></p>
                </td>
            </tr>
            <tr valign="top">
                <td colspan="3" style="padding:10px;">
                    {{ $mainInvoice->other_charges_description }}
                </td>
                <td style="position: relative; padding-top: 15px; padding-right: 15px; " align="right" class="netamount">
                    <p style="font-size:16px; color:#000;">OTHER</p>
                </td>
                <td style="background: #e7e8e9; text-align: right; font-size: 18px; color:#000;padding-top: 15px; padding-right: 15px;">
                    <p style="font-size:22px !important; color: #ff7900; font-family: 'Roboto-Regular'; ">{{number_format($mainInvoice->other_charges,2)}}</p>
                </td>
            </tr>

            <tr valign="top">
                <td colspan="3" style="">

                </td>
                <td style="position: relative; padding-top: 15px; padding-right: 15px; " align="right" class="netamount">
                    <p style="font-size:16px; color:#000;">TOTAL</p>
                </td>
                <td style="background: #e7e8e9; text-align: right; font-size: 18px; color:#000;padding-top: 15px; padding-right: 15px;">
                    <p style="font-size:22px !important; color: #ff7900; font-family: 'Roboto-Regular'; ">{{number_format(($main_invoice_total+$mainInvoice->other_charges),2)}}</p>
                </td>
            </tr>

        </table>
        <br>

        <p style="font-size: 16px; font-weight: 600;text-align: center;text-transform: uppercase;">Thank You for your business</p>
    </body>
</html>

