<table class="table table-hover">
    <tr>
        <th>Item Name</th>
        <th>Sell Price</th>
        <th>Buy Price</th>
    </tr>
    <?php
    $variantsArr = json_decode($store_product->variants);
    $basePriceArr = json_decode($store_product->base_price, true);
    $adminComisonPriceArr = json_decode($store_product->admin_commission, true);
    foreach ($variantsArr as $variant) {
        //print_r($basePriceArr); die;
        if (isset($basePriceArr[$variant->id])) {
            $basePrice = $basePriceArr[$variant->id];
            $adminComisonPrice = $adminComisonPriceArr[$variant->id];
        } else {
            $basePrice = 0;
            $adminComisonPrice = 0;
        }
        echo '<tr><td>' . $variant->title . '</td><td>' . $variant->price . '</td><td>' . number_format(($basePrice + $adminComisonPrice), 2) . '</td></tr>';
    }
    ?>
</table>
