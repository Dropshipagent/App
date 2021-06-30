@extends('layouts.app')
@section('title', 'Invoice List')
@section('main-content')
<!-- Content Header (Page header) -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="col-md-12 heading_sections">
            <!-- <h1 class="heading">Dropship <span style="color: #FFF;">Agent</span></h1> -->
        <img src="{{ asset('img/dropship.png') }}" class="logo-dropship">
        <p class="subheading"></p>
    </div>
    <div class="col-md-12 nav-tabs-custom invoices_page" style="box-shadow: none;">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#unpaid_invoice" id="unpaid_invoice_tab" data-toggle="tab">Unpaid</a></li>
            <li><a href="#paid_invoice" data-toggle="tab" id="paid_invoice_tab">Paid</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active table-responsive" id="unpaid_invoice">
                <table class="table table-hover table-striped table-bordered datatable" id="unpaid_invoice_table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Date</th>
                            <th>Order Value</th>
                            <th>Other Charges</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                </table>
            </div>
            <div class="tab-pane table-responsive" id="paid_invoice">
                <table class="table table-hover table-striped table-bordered datatable" id="paid_invoice_table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Date</th>
                            <th>Order Value</th>
                            <th>Other Charges</th>
                            <th>&nbsp;</th>
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
        $(document).on('click', '.pay_now_btn', function (e) {
            var invoiceID = $(this).data("id");
            var invoiceAmount = $(this).data("val");
            $.ajax({
                url: '{{ url("payment-info-page") }}',
                type: "GET",
                dataType: "html",
                data: {"invoice_id": invoiceID, "invoice_amount": invoiceAmount},
                success: function (data) {
                    showAlertMessage(data, "Pay Now");
                },
                error: function (xhr, status) {
                    alert("Sorry, there was a problem!");
                },
                complete: function (xhr, status) {
                    $('#acceptProductsModal').modal('show');
                }
            });
        });

        var unpaid_invoice_table = $('#unpaid_invoice_table').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[1, "desc"]],
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
                {mData: 'updated_at'},
                {mData: 'admin_price_total'},
                {mData: 'other_charges'},
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
                {mData: 'other_charges'},
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
<!-- /.content -->
@endsection