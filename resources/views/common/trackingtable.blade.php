<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tr>
                    <th>Invoice-ID</th>
                    <th>Store Domain</th>
                    <th>Order-ID</th>
                    <th>Tracking Number</th>
                    <th>Tracking Url</th>
                    <th>Tracking Company</th>
                    <th>Uploaded</th>
                </tr>
                @foreach($store_invoices as $store_invoice)
                <tr>
                    <td>{{ $store_invoice->id }}</td>
                    <td>{{ $store_invoice->store_domain }}</td>
                    <td>{{ $store_invoice->order_id }}</td>
                    <td>{{ $store_invoice->tracking_number }}</td>
                    <td><a href="{{ $store_invoice->tracking_url }}" target="_balnk">{{ $store_invoice->tracking_url }}</a></td>
                    <td>{{ $store_invoice->tracking_company }}</td>
                    <td>{{ $store_invoice->created_at }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="pull-right">
                {{ $store_invoices->links() }}
            </div>
        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->

</section>