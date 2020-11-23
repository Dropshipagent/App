<table class="table" style="width: 100%;">
    <tr>
        <th>Item Name</th>
        <th>Qty.</th>
        <th>Price</th>
        <th>Shipper Price</th>
    </tr>
    <?php
    $total_amount = 0;
    $subtotal_amount = 0;
    ?>
    @foreach($items as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->quantity }}</td>
        <td>{{ currency(($item->price*$item->quantity), 'USD', currency()->getUserCurrency()) }}</td>
        <td>
            <?php
            $shipperPriceArr = json_decode($Invoice->invoice_data, true);
            $variantPriceByAdmin = $shipperPriceArr[$item->variant_id];

            $adminComisonArr = json_decode($Invoice->commission_data, true);
            $variantCommissionByAdmin = $adminComisonArr[$item->variant_id];
            $subtotal_amount = ($variantPriceByAdmin + $variantCommissionByAdmin);
            echo currency($subtotal_amount, 'USD', currency()->getUserCurrency());
            $total_amount += $subtotal_amount;
            ?>
        </td>
    </tr>
    @endforeach
    <?php if ($Invoice->notes) { ?>
        <tr>
            <td colspan="5">
                <b>Notes:</b> {{ $Invoice->notes }}
            </td>
        </tr>
    <?php } ?>
</table>
<div class="box-footer">
    <div class="pull-right">
        <a href="javascript:void(0)" class="btn btn-block btn-danger btn-sm">Total: {{ currency($total_amount, 'USD', currency()->getUserCurrency()) }}</a>
    </div>
</div>