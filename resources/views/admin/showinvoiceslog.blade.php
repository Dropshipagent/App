@extends('admin.layouts.app')
@section('title', 'Dashboard')
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

    <div class="nav-tabs-custom" style="position: relative">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#unpaid_invoice" data-toggle="tab" id="unpaid_invoice_tab">Unpaid</a></li>
            <li><a href="#paid_invoice" data-toggle="tab" id="paid_invoice_tab">Paid</a></li>
        </ul>
        <div class="pull-right" style="position: absolute; right:10px; top:6px;">
            <div class="datatablefilters">
                <div class="searchfilter">
                    <select id="storedomain" class="form-control" style="width: 150px;">
                        <option value="">Store Domain</option>
                        <?php
                        foreach ($store as $val) {
                            ?>
                            <option>{{$val->domain}}</option>
                            <?php
                        }
                        ?>
                    </select>                        
                </div>  
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="unpaid_invoice">
                <table class="table table-hover table-striped table-bordered datatable" id="unpaid_invoice_table">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Store Domain</th>
                            <th>Shipper Price</th>
                            <th>Admin Commission</th>
                            <th>Created</th>
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
                            <th>Store Domain</th>
                            <th>Shipper Price</th>
                            <th>Admin Commission</th>
                            <th>Shipper Paid Status</th>
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
                    d.store_domain = $('#storedomain').val();
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
                    d.store_domain = $('#storedomain').val();
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
        $('#unpaid_invoice_tab').click(function () {
            unpaid_invoice_table.draw();
        });
        $('#paid_invoice_tab').click(function () {
            paid_invoice_table.draw();
        });
        
        $(document).on('click', '.shipperpaidbtn', function () {
            invoiceid = $(this).data("id");
            var r = confirm("Are you sure?");
            if (r == true) {
                $.ajax({
                    type: 'POST',
                    url: '{{ url("/admin/shipperpaidstatus_change") }}',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {"invoice_id": invoiceid, "status": 2},
                    success: function (data) {

                        if (data.data.success) {
                            paid_invoice_table.draw();
                        }
                    }
                });
            }
        });

    });

</script>
@endsection