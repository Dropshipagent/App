@extends('supplier.layouts.app')
@section('title', 'Pending Invoice Orders')
@section('main-content')
<!-- Content Header (Page header) -->
@include('supplier.layouts.header-tabs')
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="creat_asinvoive">
        <div class="table-responsive">
            <div class="datatablefilters">
                <div class="searchfilter">                            
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" id="searchbox" class="form-control" placeholder="Filter orders">
                    </div>                            
                </div>&nbsp;&nbsp;&nbsp;
                <a href="javascript:void(0)" class="btn btn-block btn-danger btn-sm create_invoice_btn">Create Invoice</a>
                &nbsp;
                <a href="{{ url('supplier/custominvoice') }}" class="btn btn-block btn-danger btn-sm">Create Custom Invoice</a>
            </div>
            {!! Form::open(array('url' => 'supplier/showbluckinvoice/'.$user->id,'id' => 'flag_submit','files'=>true,'method'=>'POST')) !!}
            <table class="table table-hover table-striped table-bordered datatable unsourced" data-page-length='10000000000'>
                <thead> 
                    <tr>
                        <th>&nbsp;</th>
                        <th>Order Number</th>
                        <th>Customer Email</th>
                        <th>Customer Name</th>
                        <th>Financial Status</th>
                        <th>Order Value</th>
                        <th>Ship To</th>
                        <th>Created</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
            </table>
            {!! Form::close() !!}
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        //metod call on create invoice button
        $('.create_invoice_btn').on('click', function () {
            var validateData = false;
            $('.flag_checkbox').each(function () {
                if (this.checked) {
                    validateData = true;
                }
            });
            if (validateData == false) {
                alert("Please select atleast one order.");
                return false;
            } else {
                if (confirm('Please confirm, really you want to create an invoice for all selected orders!')) {
                    $("#flag_submit").submit();
                }
            }
        });

        $('#searchbox').keyup(function () {
            unsourced.search($(this).val()).draw();
        });
        $('#paymentStatus,#orderStatus,#fulfillmentstatus').on('change', function (e) {
            unsourced.draw();
        });
        var unsourced = $('.unsourced').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[8, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.store_domain = "<?php echo $user->username; ?>"
                    d.financial_status = $('#paymentStatus').val();
                    d.fulfillment_status = $('#fulfillmentstatus').val();
                    d.order_status = $('#orderStatus').val();
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'create_invoice'},
                {mData: 'order_number'},
                {mData: 'email'},
                {mData: 'cust_fname'},
                {mData: 'financial_status'},
                {mData: 'order_value'},
                {mData: 'ship_to'},
                {mData: 'created_at'},
                {mData: 'items'},
            ],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']}
            ],
            "language": {
                "zeroRecords": "No order available",
                "paginate": {
                    "previous": "< ",
                    "next": " >"
                }
            }
        });
    })
</script>
@endsection