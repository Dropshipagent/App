<table class="table table-hover">
    <tr>
        <th>Item Name</th>
        <th>Qty.</th>
        <th>Price</th>
        <th>Your Price</th>
    </tr>
    <?php
    $invoiceTotal = 0;
    $invoicesubTotal = 0;
    ?>
    @foreach($orderItems as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->quantity }}</td>
        <td>{{ ($item->quantity*$item->price) }}</td>
        <td>
            <?php
            if (isset($item->productdetail->base_price) && $item->productdetail->product_status == 3) {
                $basePriceArr = json_decode($item->productdetail->base_price, true);
                $variantPriceByAdmin = (isset($basePriceArr[$item->variant_id])) ? $basePriceArr[$item->variant_id] : 0;

                $adminComisonArr = json_decode($item->productdetail->admin_commission, true);
                $variantCommissionByAdmin = (isset($adminComisonArr[$item->variant_id])) ? $adminComisonArr[$item->variant_id] : 0;
            } else {
                $variantPriceByAdmin = 0;
                $variantCommissionByAdmin = 0;
            }
            $invoiceTotal += ($variantPriceByAdmin * $item->quantity);
            $invoicesubTotal = ($variantPriceByAdmin * $item->quantity);
            $variantCommissionByAdmin = ($variantCommissionByAdmin * $item->quantity);
            echo Form::hidden('commission_data[]', $variantCommissionByAdmin);
            echo Form::hidden('variant_id[]', $item->variant_id);
            echo Form::number('variant_supplier_price[]', $invoicesubTotal, array('step' => 'any', 'min' => '0', 'max' => $invoicesubTotal, 'readonly', 'required' => 'required', 'class' => 'form-control'));
            ?>
        </td>
    </tr>
    @endforeach
    <tr>
        <td colspan="3"></td>
        <td><strong>Invoice Total : </strong>{{ $invoiceTotal }}</td>
    </tr>
</table>