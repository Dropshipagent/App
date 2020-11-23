@extends('shipper.layouts.app')
@section('title', 'All Orders')
@section('main-content')
<!-- Content Header (Page header) -->
@include('shipper.layouts.header-tabs')
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-body table-responsive">
            <div class="datatablefilters">
                <div class="searchfilter">                            
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" id="searchbox" class="form-control" placeholder="Filter orders">
                    </div>                            
                </div>
                <div class="searchfilter">
                    <select id="paymentStatus" class="form-control" >
                        <option value="">Payment Status</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Unpaid</option>
                    </select>                        
                </div>
                <div class="searchfilter">
                    <select id="fulfillmentstatus" class="form-control" >
                        <option value="">Fulfillment status</option>
                        <option value="fulfilled">Fulfilled</option>
                        <option value="pending">Unfulfilled</option>
                    </select>                        
                </div>   
            </div>
            <table class="table table-hover table-striped table-bordered datatable unsourced">
                <thead> 
                    <tr>
                        <th>Order Number</th>
                        <th>Created</th>
                        <th>Customer Email</th>
                        <th>Customer Name</th>
                        <th>Total</th>
                        <th>Financial Status</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
<!-- /.content -->
<!-- Modal -->
<div id="viewOrderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Order Detail</h4>
            </div>
            <div class="modal-body clearfix">
                <div class="orderResult">
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function () {

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
            "order": [[0, "desc"]],
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
                {mData: 'order_number'},
                {mData: 'created_at'},
                {mData: 'email'},
                {mData: 'cust_fname'},
                {mData: 'order_value'},
                {mData: 'financial_status'},
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
        
        //Code to ajax call for order details
        $(document).on('click', '.viewOrderDetail', function (e) {
            var orderID = $(this).data('id');
            // show Modal
            $.ajax({
                url: '{{ url("order_detail") }}/' + orderID,
                type: "GET",
                dataType: "html",
                success: function (data) {
                    $('.orderResult').html(data);
                },
                error: function (xhr, status) {
                    alert("Sorry, there was a problem!");
                },
                complete: function (xhr, status) {
                    $('#viewOrderModal').modal('show');
                }
            });
        });
    })
</script>
@endsection