<table class="table">
    <?php
    $orderdetail = json_decode($orderdetail->items, true);
    ?>
    <tr>
        <th>Name</th>
        <td>{{$orderdetail['shipping_address']['name']}}</td>
    </tr>
    <tr>
        <th>Address</th>
        <td>{{$orderdetail['shipping_address']['address1'].' '.$orderdetail['shipping_address']['address2']}}</td>
    </tr>
    <tr>
        <th>City</th>
        <td>{{$orderdetail['shipping_address']['city']}}</td>
    </tr>
    <tr>
        <th>Zip Code</th>
        <td>{{$orderdetail['shipping_address']['zip']}}</td>
    </tr>
    <tr>
        <th>Province</th>
        <td>{{$orderdetail['shipping_address']['province']}}</td>
    </tr>
    <tr>
        <th>Country</th>
        <td>{{$orderdetail['shipping_address']['country']}}</td>
    </tr>
</table>