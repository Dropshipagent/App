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
            <tr class="topheading">
                <td valign="top" style="width:150px;">
                    <div style="padding:15px; background-color:#367fa9"><img src="{{url('admin/dist/img/logo.png')}}" > </div> 
                </td>
                <td align="right" class="campanyinfo">
                    <div class="head">{{ $storeData->username }}</div>
                    <div class="info">{{ $storeData->email }}</div>
                    <div class="info">{{ $storeData->address }}</div>
                </td>
            </tr>
        </table>
        <main>                
            <table cellpadding="0" cellspacing="0" border-color="#e7e8e9" class="listdata" style="width:90%; margin-top: 25px; max-height:400px; min-height: 400px;">    
                <tr align="left" class="heading">
                    <td style="border:1px solid #e7e8e9; border-right:none; border-left:none;  width:120px; height:30px; font-family: 'Roboto-Regular';">Item</td>
                    <td style="border:1px solid #e7e8e9; border-right:none; width:250px; padding-left: 15px; ">Name</td>
                    <td style="border:1px solid #e7e8e9; border-right:none; width:60px; text-align: center;">Price</td>
                    <td style="border:1px solid #e7e8e9; border-right:none; width:100px; text-align: center;">Quantity</td>
                    <td style="border:none; border-top:1px solid #e7e8e9; background: #e7e8e9; text-align: center; width: 100px;padding-left: 15px;">Amount</td>
                </tr>
                <?php
                $main_invoice_total = 0;
                foreach ($invoice_items as $oKey => $oVal) {
                    $price_by_admin = ($oVal['product_admin_price'] / $oVal['product_quantity']);
                    $admin_commission = ($oVal['product_admin_commission'] / $oVal['product_quantity']);
                    $product_price = ($price_by_admin + $admin_commission);
                    ?>
                    <tr valign="center">
                        <td class="items">{{ $oKey }}</td>
                        <td class="description">{{ $oVal['product_title'] }}</td>
                        <td class="price">{{ $product_price }}</td>
                        <td class="quantity"> {{ $oVal['product_quantity'] }}</td>
                        <td class="amount" style="background: #e7e8e9;">{{ number_format(($product_price*$oVal['product_quantity']), 2) }}</td>
                    </tr>
                    <?php
                    $main_invoice_total += ($product_price * $oVal['product_quantity']);
                }
                ?>
                <tr valign="top">
                    <td colspan="3" style="border:none;border-top:1px solid #e7e8e9;">

                    </td>
                    <td style="border:none;border-top:1px solid #e7e8e9;position: relative; padding-top: 15px; padding-right: 15px; " align="right" class="netamount">
                        <div style="width:50px; height:3px; position: absolute;top:70px; right:-10px; color: #e7e8e9; background: #ff7900"></div>
                        <p style="font-size:16px; color:#000;">Net Amount</p>
                    </td>
                    <td style="border:none; background: #e7e8e9; text-align: right; font-size: 18px; color:#000;padding-top: 15px; padding-right: 15px;">
                        <p style="font-size:22px !important; color: #ff7900; font-family: 'Roboto-Regular'; "><b>{{number_format($main_invoice_total,2)}}</b></p>
                    </td>
                </tr>
            </table>
        </main>
    </body>
</html>

