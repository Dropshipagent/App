<section class="content">
    <!-- Default box -->

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#unpaid_invoice" data-toggle="tab">Unpaid</a></li>
            <li><a href="#paid_invoice" data-toggle="tab">Paid</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active table-responsive" id="unpaid_invoice">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Order ID</th>
                            <th>Order Number</th>
                            <th>Customer Email</th>
                            <th>Customer Name</th>
                            <th>Payment Gateway</th>
                            <th>Financial Status</th>
                            <th>Order Value</th>
                            <th>Ship To</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unpaidInvoices_logs as $invoices_log)
                        <?php
                        $total_amount = 0;
                        $order = $invoices_log->orderdetail;
                        ?>
                        <tr>
                            <td>{{ $invoices_log->id }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->email }}</td>
                            <td>{{ $order->cust_fname }}</td>
                            <td>{{ $order->payment_gateway }}</td>
                            <td>{{ $order->financial_status }}</td>
                            <td>{{ $order->order_value }}</td>
                            <td>{{ $order->ship_to }}</td>
                            <td>{{ $order->created_at }}</td>
                        </tr>
                        <tr>
                            <td colspan="10">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Qty.</th>
                                        <th>Price</th>
                                        <th>Your Base Price</th>
                                        <th>Supplier Price</th>
                                    </tr>
                                    @foreach($order->itemsarr as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ currency($item->price, 'USD', currency()->getUserCurrency()) }}</td>
                                        <td>
                                            <?php
                                            $basePriceArr = unserialize($item->productdetail->base_price);
                                            echo currency($basePriceArr[$item->variant_id], 'USD', currency()->getUserCurrency());
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $supplierPriceArr = unserialize($invoices_log->invoice_data);
                                            echo currency($supplierPriceArr[$item->variant_id], 'USD', currency()->getUserCurrency());
                                            $total_amount += $supplierPriceArr[$item->variant_id];
                                            ?>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                                @if(auth()->user()->role == 2 && $invoices_log->paid_status == 0)
                                <div class="box-footer">
                                    <div class="pull-right">
                                        <a href="javascript:void(0)" data-id="{{ $invoices_log->id }}" data-val="{{ $total_amount }}" class="btn btn-block btn-danger btn-sm pay_now_btn">Pay Now {{ currency($total_amount, 'USD', currency()->getUserCurrency()) }}</a>
                                    </div>
                                </div>
                                @endif    
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane table-responsive" id="paid_invoice">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Order ID</th>
                            <th>Order Number</th>
                            <th>Customer Email</th>
                            <th>Customer Name</th>
                            <th>Payment Gateway</th>
                            <th>Financial Status</th>
                            <th>Order Value</th>
                            <th>Ship To</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paidInvoices_logs as $invoices_log)
                        <?php
                        $total_amount = 0;
                        $order = $invoices_log->orderdetail;
                        ?>
                        <tr>
                            <td>{{ $invoices_log->id }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->email }}</td>
                            <td>{{ $order->cust_fname }}</td>
                            <td>{{ $order->payment_gateway }}</td>
                            <td>{{ $order->financial_status }}</td>
                            <td>{{ $order->order_value }}</td>
                            <td>{{ $order->ship_to }}</td>
                            <td>{{ $order->created_at }}</td>
                        </tr>
                        <tr>
                            <td colspan="10">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Qty.</th>
                                        <th>Price</th>
                                        <th>Your Base Price</th>
                                        <th>Supplier Price</th>
                                    </tr>
                                    @foreach($order->itemsarr as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ currency($item->price, 'USD', currency()->getUserCurrency()) }}</td>
                                        <td>
                                            <?php
                                            $basePriceArr = unserialize($item->productdetail->base_price);
                                            echo currency($basePriceArr[$item->variant_id], 'USD', currency()->getUserCurrency());
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $supplierPriceArr = unserialize($invoices_log->invoice_data);
                                            echo currency($supplierPriceArr[$item->variant_id], 'USD', currency()->getUserCurrency());
                                            $total_amount += $supplierPriceArr[$item->variant_id];
                                            ?>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                                @if(auth()->user()->role == 2 && $invoices_log->paid_status == 0)
                                <div class="box-footer">
                                    <div class="pull-right">
                                        <a href="javascript:void(0)" data-id="{{ $invoices_log->id }}" data-val="{{ $total_amount }}" class="btn btn-block btn-danger btn-sm pay_now_btn">Pay Now {{ currency($total_amount, 'USD', currency()->getUserCurrency()) }}</a>
                                    </div>
                                </div>
                                @endif    
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.tab-content -->
    </div>
</section>

<!-- Modal -->
<div id="payNowModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Pya Now</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="content">
                        <form class="" action="{{ url('/invoice_checkout') }}" method="post">
                            {{ csrf_field() }}
                            {!! Form::hidden('invoiceID', null, array('class' => 'invoice_id')) !!}
                            {!! Form::hidden('camount', null, array('class' => 'invoice_amount')) !!}
                            <h3>Credit Card Information</h3>
                            <div class="form-group">
                                <label for="cnumber">Card Number</label>
                                <input type="text" class="form-control" id="cnumber" name="cnumber" placeholder="Enter Card Number">
                            </div>
                            <div class="form-group">
                                <label for="card-expiry-month">Expiration Month</label>
                                {{ Form::selectMonth(null, null, ['name' => 'card_expiry_month', 'class' => 'form-control', 'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="card-expiry-year">Expiration Year</label>
                                {{ Form::selectYear(null, date('Y'), date('Y') + 10, null, ['name' => 'card_expiry_year', 'class' => 'form-control', 'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="ccode">Card Code</label>
                                <input type="text" class="form-control" id="ccode" name="ccode" placeholder="Enter Card Code">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        //show modal popup jquery
        $(".pay_now_btn").click(function () {
            $('.invoice_id').val($(this).data("id"));
            $('.invoice_amount').val($(this).data("val"));
            // show Modal
            $('#payNowModal').modal('show');
        });
    });
</script>