@extends('admin.layouts.app')
@section('title', 'Invoice Detail')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Invoices List
        <small>list of invoices which created for selected store.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('/admin/showinvoiceslog') }}">Invoices Logs</a></li>
    </ol>
</section>

<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-body table-responsive">
            <div class="datatablefilters">
                <a href="javascript:history.back()" class="btn btn-block btn-danger btn-sm">Back</a>
            </div>
            <table class="table table-hover table-striped table-bordered datatable" id="store_invoice_table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Number</th>
                        <th>Customer Email</th>
                        <th>Customer Name</th>
                        <th>Payment Gateway</th>
                        <th>Order Value</th>
                        <th>Ship To</th>
                        <th>Created</th>
                        <th>Items</th>
                        <th>Ship Address</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {
        var store_invoice_table = $('#store_invoice_table').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[0, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.paid_status = "0";
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'order_id'},
                {mData: 'order_number'},
                {mData: 'email'},
                {mData: 'cust_fname'},
                {mData: 'payment_gateway'},
                {mData: 'order_value'},
                {mData: 'ship_to'},
                {mData: 'created_at'},
                {mData: 'item'},
                {mData: 'ship_address'},
            ],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']}
            ],
            "language": {
                "zeroRecords": "No invoice available",
                "paginate": {
                    "previous": "< ",
                    "next": " >"
                }
            }
        });
    });
</script>
@endsection