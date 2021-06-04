<table class="table">
    <tr>
        <th>Item Name</th>
        <th>Qty.</th>
        <th>Price</th>
        <th>Supplier Price</th>
        @if(auth()->user()->role == 1)
        <th>Admin Commission</th>
        @endif
    </tr>
    <?php
    $invoiceTotal = 0;
    $commissionTotal = 0;
    ?>
    @foreach($items as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->quantity }}</td>
        <td>${{ ($item->quantity*$item->price) }}</td>
        <td>
            <?php
            $supplierPriceArr = json_decode($Invoice->invoice_data, true);
            echo ($supplierPriceArr[$item->variant_id] > 0) ? '$' . $supplierPriceArr[$item->variant_id] : '-';
            $invoiceTotal += $supplierPriceArr[$item->variant_id];
            ?>
        </td>
        @if(auth()->user()->role == 1)
        <td>
            <?php
            $adminComisonArr = json_decode($Invoice->commission_data, true);
            echo '$' . $variantCommissionByAdmin = $adminComisonArr[$item->variant_id];
            $commissionTotal += $variantCommissionByAdmin;
            ?>
        </td>
        @endif
    </tr>
    @endforeach
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <th>Total</th>
        <td>{{number_format($invoiceTotal,2)}}</td>
        @if(auth()->user()->role == 1)
        <td>{{number_format($commissionTotal,2)}}</td>
        @endif
    </tr>
    <?php if ($Invoice->notes) { ?>
        <tr>
            <td colspan="5">
                <b>Notes:</b> {{ $Invoice->notes }}
            </td>
        </tr>
    <?php } ?>
</table>