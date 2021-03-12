<table class="table table-hover">
    <tr>
        <th>Item Name</th>
        <th>Qty.</th>
        <th>Price</th>
    </tr>
    @foreach($order->itemsarr as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->quantity }}</td>
        <td><?php
            $subtotal_amount = ($item->quantity * $item->price);
            echo currency($subtotal_amount, 'USD', currency()->getUserCurrency());
            ?></td>
    </tr>
    @endforeach
</table>
