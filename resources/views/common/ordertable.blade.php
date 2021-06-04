<!-- Default box -->
<div class="">
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>ID</th>
                @if($user->role == 2)
                <th>Supplier assign 
                    {!! Form::open(array('id' => 'flag_filter','files'=>true,'method'=>'GET')) !!}
                    <?php
                    if (isset($_REQUEST['type'])) {
                        $typeKey = $_REQUEST['type'];
                    } else {
                        $typeKey = "";
                    }
                    ?>
                    <select name="type" class="search_type_tag form-control" required="">
                        <option value="">-- Select Filter --</option>
                        <option value="filterSupplier" <?php echo ($typeKey == "filterSupplier") ? "selected" : ""; ?>>Assigned Orders</option>
                        <option value="all" <?php echo ($typeKey == "all") ? "selected" : ""; ?>>All Order</option>
                    </select>
                    {!! Form::close() !!}
                </th>
                @endif
                <th>Order ID</th>
                <th>Order Number</th>
                <th>Customer Email</th>
                <th>Customer Name</th>
                <th>Payment Gateway</th>
                <th>Financial Status</th>
                <th>Order Value</th>
                <th>Ship To</th>
                <th>Created</th>
                <th>&nbsp;</th>
            </tr>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                @if($user->role == 2)
                <td class="text-center">
                    <?php
                    if ($order->assign_supplier == 1) {
                        echo "Already Assigned";
                    } else {
                        ?>
                        <input type="checkbox" class="flag_checkbox" name="flag[]" data-id="flagData" value="{{ $order->order_id }}" />
                    <?php } ?>
                </td>
                @endif
                <td>{{ $order->order_id }}</td>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->email }}</td>
                <td>{{ $order->cust_fname }}</td>
                <td>{{ $order->payment_gateway }}</td>
                <td>{{ $order->financial_status }}</td>
                <td>{{ $order->order_value }}</td>
                <td>{{ $order->ship_to }}</td>
                <td>{{ $order->created_at }}</td>
                <td>
                    <div class="box box-warning">
                        <div class="box-body">
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
                                    <td>{{ $item->price }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <!-- /.box-body -->
</div>
<div class="box-footer">
    <div class="pull-right">
        {{ $orders->appends(request()->input())->links() }}
    </div>
</div>
<!-- /.box-footer-->
<!-- /.box -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.search_type_tag').on('change', function () {
            $("#flag_filter").submit();
        });
    });
</script>