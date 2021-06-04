<table class="table table-hover" border="1">
    <tr>
        <th>Order Number</th>
        <th>Email</th>
        <th>Financial Status</th>
        <th>Paid at</th>
        <th>Fulfillment Status</th>
        <th>Fulfilled at</th>
        <th>Accepts Marketing</th>
        <th>Currency</th>
        <th>Subtotal</th>
        <th>Shipping</th>
        <th>Taxes</th>
        <th>Total</th>
        <th>Discount Code</th>
        <th>Discount Amount</th>
        <th>Shipping Method</th>
        <th>Created at</th>
        <th>Lineitem quantity</th>
        <th>Lineitem name</th>
        <th>Lineitem price</th>
        <th>Lineitem compare at price</th>
        <th>Lineitem sku</th>
        <th>Lineitem requires shipping</th>
        <th>Lineitem taxable</th>
        <th>Lineitem fulfillment status</th>
        <th>Billing Name</th>
        <th>Billing Street</th>
        <th>Billing Address1</th>
        <th>Billing Address2</th>
        <th>Billing Company</th>
        <th>Billing City</th>
        <th>Billing Zip</th>
        <th>Billing Province</th>
        <th>Billing Country</th>
        <th>Billing Phone</th>
        <th>Shipping Name</th>
        <th>Shipping Street</th>
        <th>Shipping Address1</th>
        <th>Shipping Address2</th>
        <th>Shipping Company</th>
        <th>Shipping City</th>
        <th>Shipping Zip</th>
        <th>Shipping Province</th>
        <th>Shipping Country</th>
        <th>Shipping Phone</th>
        <th>Notes</th>
        <th>Note Attributes</th>
        <th>Cancelled at</th>
        <th>Payment Method</th>
        <th>Payment Reference</th>
        <th>Refunded Amount</th>
        <th>Vendor</th>
        <th>Id</th>
        <th>Tags</th>
        <th>Risk Level</th>
        <th>Source</th>
        <th>Lineitem discount</th>
        <th>Tax 1 Name</th>
        <th>Tax 1 Value</th>
        <th>Tax 2 Name</th>
        <th>Tax 2 Value</th>
        <th>Tax 3 Name</th>
        <th>Tax 3 Value</th>
        <th>Tax 4 Name</th>
        <th>Tax 4 Value</th>
        <th>Tax 5 Name</th>
        <th>Tax 5 Value</th>
        <th>Phone</th>
        <th>Receipt Number</th>
    </tr>
    <?php
    $odrName = "";
    ?>
    @foreach($orderItems as $orderItem)
    <?php
    $allOData = isset($orderItem->orderdetail->items) ? json_decode($orderItem->orderdetail->items) : (object) [];
    //echo "<pre>"; print_r($allOData); 
    $tax_lines = isset($orderItem->tax_lines) ? json_decode($orderItem->tax_lines) : (object) [];
    $total_discount_set = isset($orderItem->total_discount_set) ? json_decode($orderItem->total_discount_set) : (object) [];
//    echo "<pre>"; print_r($total_discount_set); die;
    if ($odrName !== $allOData->name) {
        $odrName = $allOData->name;
        $discountCodes = json_decode(json_encode($allOData->discount_codes), true);
        $discountCodesString = '';
        if (count($discountCodes) > 0) {
            foreach ($discountCodes as $discountCode) {
                $discountCodesString .= implode(",", $discountCode) . ',';
            }
        }

        $noteAttributes = json_decode(json_encode($allOData->note_attributes), true);
        $noteAttributesString = '';
        if (count($noteAttributes) > 0) {
            foreach ($noteAttributes as $noteAttribute) {
                $noteAttributesString .= $noteAttribute['name'] . ' = ' . $noteAttribute['value'] . ',';
            }
        }
        ?>
        <tr>
            <td>{{ $allOData->name }}</td>
            <td>{{ $orderItem->orderdetail->email }}</td>
            <td>{{ $allOData->financial_status }}</td>
            <td>{{ $allOData->created_at }}</td>
            <td>{{ $orderItem->fulfillment_status ?? "unfulfilled" }}</td>
            <td>&nbsp;</td>
            <td>{{ (empty($allOData->buyer_accepts_marketing))? "no" : $allOData->buyer_accepts_marketing }}</td>
            <td>{{ $allOData->currency }}</td>
            <td>{{ $allOData->subtotal_price }}</td>
            <td>{{ "0" }}</td>
            <td>{{ $allOData->total_tax }}</td>
            <td>{{ $allOData->total_price }}</td>
            <td>{{ $discountCodesString }}</td>
            <td>{{ $allOData->total_discounts }}</td>
            <td>{{ $orderItem->shipping_method }}</td>
            <td>{{ $orderItem->created_at }}</td>
            <td>{{ $orderItem->quantity }}</td>
            <td>{{ $orderItem->name }}</td>
            <td>{{ $orderItem->price }}</td>
            <td>&nbsp;</td>
            <td>{{ $orderItem->sku }}</td>
            <td>{{ ($orderItem->requires_shipping == 1)? "true" : "false" }}</td>
            <td>{{ ($orderItem->taxable == 1)? "true" : "false" }}</td>
            <td>{{ (empty($orderItem->fulfillment_status))? "pending" : $orderItem->fulfillment_status }}</td>
            <td>{{ (isset($allOData->billing_address->name)) ? $allOData->billing_address->name : '' }}</td>
            <td>{{ (isset($allOData->billing_address->city)) ? $allOData->billing_address->city : '' }}</td>
            <td>{{ (isset($allOData->billing_address->address1)) ? $allOData->billing_address->address1 : '' }}</td>
            <td>{{ (isset($allOData->billing_address->address2)) ? $allOData->billing_address->address2 : '' }}</td>
            <td>{{ (isset($allOData->billing_address->company)) ? $allOData->billing_address->company : '' }}</td>
            <td>{{ (isset($allOData->billing_address->city)) ? $allOData->billing_address->city : '' }}</td>
            <td>{{ (isset($allOData->billing_address->zip)) ? $allOData->billing_address->zip : '' }}</td>
            <td>{{ (isset($allOData->billing_address->province_code)) ? $allOData->billing_address->province_code : '' }}</td>
            <td>{{ (isset($allOData->billing_address->country_code)) ? $allOData->billing_address->country_code : '' }}</td>
            <td>{{ (isset($allOData->billing_address->phone)) ? $allOData->billing_address->phone : '' }}</td>
            <td>{{ (isset($allOData->shipping_address->name)) ? $allOData->shipping_address->name : '' }}</td>
            <td>{{ (isset($allOData->shipping_address->city)) ? $allOData->shipping_address->city : '' }}</td>
            <td>{{ (isset($allOData->shipping_address->address1)) ? $allOData->shipping_address->address1 : '' }}</td>
            <td>{{ (isset($allOData->shipping_address->address2)) ? $allOData->shipping_address->address2 : '' }}</td>
            <td>{{ (isset($allOData->shipping_address->company)) ? $allOData->shipping_address->company : '' }}</td>
            <td>{{ (isset($allOData->shipping_address->city)) ? $allOData->shipping_address->city : '' }}</td>
            <td>{{ (isset($allOData->shipping_address->zip)) ? $allOData->shipping_address->zip : '' }}</td>
            <td>{{ (isset($allOData->shipping_address->province_code)) ? $allOData->shipping_address->province_code : '' }}</td>
            <td>{{ (isset($allOData->shipping_address->country_code)) ? $allOData->shipping_address->country_code : '' }}</td>
            <td>{{ (isset($allOData->shipping_address->phone)) ? $allOData->shipping_address->phone : '' }}</td>
            <td>{{ $allOData->note }}</td>
            <td>{{ $noteAttributesString }}</td>
            <td>{{ $allOData->cancelled_at }}</td>
            <td>{{ $allOData->gateway }}</td>
            <td>{{ $allOData->reference }}</td>
            <td>&nbsp;</td>
            <td>{{ $orderItem->vendor }}</td>
            <td>{{ $allOData->id }}</td>
            <td>{{ $allOData->tags }}</td>
            <td>Low</td>
            <td>{{ $allOData->source_name }}</td>
            <td>{{ $total_discount_set->shop_money->amount }}</td>
            <td>&nbsp;</td>
            <td>{{ $allOData->total_tax }}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    <?php } else { ?>
        <tr>
            <td>{{ $allOData->name }}</td>
            <td>{{ $orderItem->orderdetail->email }}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>{{ $orderItem->created_at }}</td>
            <td>{{ $orderItem->quantity }}</td>
            <td>{{ $orderItem->name }}</td>
            <td>{{ $orderItem->price }}</td>
            <td>&nbsp;</td>
            <td>{{ $orderItem->sku }}</td>
            <td>{{ ($orderItem->requires_shipping == 1)? "true" : "false" }}</td>
            <td>{{ ($orderItem->taxable == 1)? "true" : "false" }}</td>
            <td>{{ (empty($orderItem->fulfillment_status))? "pending" : $orderItem->fulfillment_status }}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>{{ $orderItem->vendor }}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>{{ $total_discount_set->shop_money->amount }}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php
    }
    ?>
    @endforeach
</table>