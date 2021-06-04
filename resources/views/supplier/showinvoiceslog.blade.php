@extends('supplier.layouts.app')
@section('title', 'Invoice List')
@section('main-content')
<!-- Content Header (Page header) -->
@include('supplier.layouts.header-tabs')

<section class="content">
    <!-- Default box -->

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#unpaid_invoice" data-toggle="tab" id="unpaid_invoice_tab">Unpaid</a></li>
            <li><a href="#paid_invoice" data-toggle="tab" id="paid_invoice_tab">Paid</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active table-responsive" id="unpaid_invoice">
                <table class="table table-hover table-striped table-bordered datatable" id="unpaid_invoice_table">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Store Domain</th>
                            <th>Order Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="tab-pane" id="paid_invoice">
                <table class="table table-hover table-striped table-bordered datatable" id="paid_invoice_table">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Store Domain</th>
                            <th>Order Value</th>
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
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'id'},
                {mData: 'created_at'},
                {mData: 'store_domain'},
                {mData: 'admin_price_total'},
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
                    d.paid_status = "2";
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'id'},
                {mData: 'created_at'},
                {mData: 'store_domain'},
                {mData: 'admin_price_total'},
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

    });
</script>
@endsection