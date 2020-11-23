<table class="table table-hover">
    <tr>
        <th>Item Name</th>
        <th>Sell Price($)</th>
        <th>Base Price($)</th>
        <th>Admin Commission($)</th>
    </tr>
    <?php
    $variantsArr = json_decode($store_product->variants);
    $basePriceArr = json_decode($store_product->base_price, true);
    $adminComisonPriceArr = json_decode($store_product->admin_commission, true);
    foreach ($variantsArr as $variant) {
        if (isset($basePriceArr[$variant->id])) {
            $basePrice = number_format($basePriceArr[$variant->id], 2);
            $adminComisonPrice = number_format($adminComisonPriceArr[$variant->id], 2);
        } else {
            $basePrice = 0;
            $adminComisonPrice = 0;
        }
        echo '<tr><td>' . $variant->title . '</td><td>' . $variant->price . '</td><td>' . $basePrice . '</td><td>' . $adminComisonPrice . '</td></tr>';
    }
    ?>
</table>