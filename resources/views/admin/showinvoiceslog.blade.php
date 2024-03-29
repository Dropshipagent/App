@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
@include('admin.layouts.header-tabs')
<section class="content-header">
    <h1>
        Store [{{ Session::get('selected_store_id') }}] Invoices
        <small>list of invoices which created for selected store.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('/admin/showinvoiceslog') }}">Invoices Logs</a></li>
    </ol>
</section>

<section class="content">
    <!-- Default box -->

    <div class="nav-tabs-custom" style="position: relative">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#unpaid_invoice" data-toggle="tab" id="unpaid_invoice_tab">Unpaid</a></li>
            <li><a href="#paid_invoice" data-toggle="tab" id="paid_invoice_tab">Paid</a></li>
            <li><a href="#supplier_paid_invoice" data-toggle="tab" id="supplier_paid_invoice_tab">Paid for supplier</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active table-responsive" id="unpaid_invoice">
                <table class="table table-hover table-striped table-bordered datatable" id="unpaid_invoice_table">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Store Domain</th>
                            <th>Supplier Price</th>
                            <th>Admin Commission</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="tab-pane table-responsive" id="paid_invoice">
                <table class="table table-hover table-striped table-bordered datatable" id="paid_invoice_table">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Store Domain</th>
                            <th>Supplier Price</th>
                            <th>Admin Commission</th>
                            <th>Payment Info</th>
                            <th>Decline Payment</th>
                            <th>Supplier Paid Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="tab-pane table-responsive" id="supplier_paid_invoice">
                <table class="table table-hover table-striped table-bordered datatable" id="supplier_paid_invoice_table">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Store Domain</th>
                            <th>Supplier Price</th>
                            <th>Admin Commission</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- /.tab-content -->
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {
        //show modal popup jquery
        var unpaid_invoice_table = $('#unpaid_invoice_table').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[0, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.paid_status = "0";
                    d.store_domain = "{{ Session::get('selected_store_id') }}";
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'id'},
                {mData: 'store_domain'},
                {mData: 'admin_price_total'},
                {mData: 'admin_commission_total'},
                {mData: 'created_at'},
                {mData: 'action'},
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

        var paid_invoice_table = $('#paid_invoice_table').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[0, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.paid_status = "1";
                    d.store_domain = "{{ Session::get('selected_store_id') }}";
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'id'},
                {mData: 'store_domain'},
                {mData: 'admin_price_total'},
                {mData: 'admin_commission_total'},
                {mData: 'payment_info'},
                {mData: 'decline_payment'},
                {mData: 'paid_status'},
                {mData: 'created_at'},
                {mData: 'action'},
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

        var supplier_paid_invoice_table = $('#supplier_paid_invoice_table').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[0, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.paid_status = "2";
                    d.store_domain = "{{ Session::get('selected_store_id') }}";
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'id'},
                {mData: 'store_domain'},
                {mData: 'admin_price_total'},
                {mData: 'admin_commission_total'},
                {mData: 'created_at'},
                {mData: 'action'},
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
        $('#unpaid_invoice_tab').click(function () {
            unpaid_invoice_table.draw();
        });
        $('#paid_invoice_tab').click(function () {
            paid_invoice_table.draw();
        });
        $('#supplier_paid_invoice_tab').click(function () {
            supplier_paid_invoice_table.draw();
        });

        //show modal popup jquery
        $(document).on('click', '.view_payment_info', function (e) {
            var imagePath = $(this).data("id");
            showAlertMessage("<img src='" + imagePath + "' style='max-width:100%'>", "Payment Info");
        });

        $(document).on('click', '.update-invoice-status', function () {
            var invoiceid = $(this).data("id");
            var invoiceStatus = $(this).data("val");
            var r = confirm("Are you sure?");
            if (r == true) {
                $.ajax({
                    type: 'POST',
                    url: '{{ url("/admin/update_invoice_status") }}',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {"invoice_id": invoiceid, "status": invoiceStatus},
                    success: function (data) {
                        if (data.success) {
                            paid_invoice_table.draw();
                            showAlertMessage(data.message, "Payment Status");
                        }
                    }
                });
            }
        });

    });

</script>
@endsection