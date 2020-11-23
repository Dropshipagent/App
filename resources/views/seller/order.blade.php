@extends('layouts.app')
@section('title', 'Order List')
@section('main-content')
<!-- Content Wrapper. Contains page content -->
<!-- Main content -->
<section class="content">

    <!-- Default box -->

    <div class="nav-tabs-custom" style="position: relative">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#all" data-toggle="tab" id="all_tab">All</a></li>
            <li><a href="#open" data-toggle="tab" id="open_tab">Open</a></li>
            <li><a href="#sourcedandpending" data-toggle="tab" id="sourcedandpending_tab">Unfulfilled</a></li>
            <li><a href="#fulfilled" data-toggle="tab" id="fulfilled_tab">Fulfilled</a></li>
            <li><a href="#previous_export" data-toggle="tab" id="previous_export_tab">Previous Export</a></li>
        </ul>
        @if(helGetShipperID(Auth::user()->id) > 0)
        <div class="pull-right" style="position: absolute; right:10px; top:6px;">
            <a href="javascript:void(0)" class="btn btn-block btn-danger btn-sm assign_shipper_btn">Export to supplier</a>
        </div>
        @endif
        <div class="tab-content">
            <div class="tab-pane active" id="all">
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


                <table class="table table-striped table-bordered datatable all">
                    <thead> 
                        <tr>
                            <th>Shipper assign</th>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Customer Email</th>
                            <th>Customer Name</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>


            <div class="tab-pane" id="open">

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


                <table class="table table-striped table-bordered datatable open">
                    <thead> 
                        <tr>
                            <th>Shipper assign</th>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Customer Email</th>
                            <th>Customer Name</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- second tab -->
            <div class="tab-pane" id="sourcedandpending">
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
                <table class="table table-striped table-bordered datatable sourcedandpending">
                    <thead>
                        <tr>
                            <th>Shipper assign</th>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Customer Email</th>
                            <th>Customer Name</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- third tab -->

            <div class="tab-pane" id="fulfilled">
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
                    <!--div class="searchfilter">
                        <select id="fulfillmentstatus" class="form-control" >
                            <option value="">Fulfillment status</option>
                            <option value="fulfilled">Fulfilled</option>
                            <option value="pending">Unfulfilled</option>
                        </select>                        
                    </div-->
                </div>
                <table class="table table-striped table-bordered datatable fulfilled">
                    <thead>
                        <tr>
                            <th>Shipper assign</th>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Customer Email</th>
                            <th>Customer Name</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- fourth tab -->

            <div class="tab-pane" id="previous_export">
                <table class="table table-striped table-bordered datatable previous_export">
                    <thead>
                        <tr>
                            <th>Log File</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>          
    </div>
    <!-- /.box-footer-->
    <!-- /.box -->

</section>
<!-- /.content -->
<!-- /.content-wrapper -->
<!-- Modal -->
<div id="shipperModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        {!! Form::open(array('url' => 'orders/export_csv_flag','id' => 'flag_submit','files'=>true,'method'=>'POST')) !!}
        {!! Form::hidden('order_ids', null, array('class' => 'order_ids')) !!}
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Export and Assign to Shipper</h4>
            </div>
            <div class="modal-body clearfix">
                <div class="col-md-12 clearfix">
                    <div class="col-md-8 clearfix">
                        <div class="form-group clearfix">
                            <strong>Export and Assign:</strong><br>
                            {{ Form::radio('result', 'c' , true, ['id'=>'labelC']) }} {{ Form::label('labelC', 'Selected orders') }}<br>
                            {{ Form::radio('result', 'b' , false, ['id'=>'labelB']) }} {{ Form::label('labelB', 'All unassigned orders') }}<br>
                            {{ Form::radio('result', 'a' , false, ['id'=>'labelA']) }} {{ Form::label('labelA', 'Current page') }}<br>
                        </div>    
                    </div>
                    <div class="col-md-4">
                        <br>

                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="" class="btn btn-primary submit_type_flag">Submit</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {



        $('.search_type_tag').on('change', function () {
            $("#flag_filter").submit();
        });


        //show modal popup jquery
        $(".assign_shipper_btn").click(function () {
            // show Modal
            $('#shipperModal').modal('show');
        });
        $('.submit_type_flag').on('click', function () {
            var radioVal = $("input[name='result']:checked").val();
            var validateData = false;
            if (radioVal == "a") {
                var sList = "";
                $('.flag_checkbox').each(function () {
                    sList += $(this).val() + "#";
                    validateData = true;
                });
                $('.order_ids').val(sList);
            } else if (radioVal == "b") {
                $('.order_ids').val("##");
                validateData = true;
            } else {
                var sList = "";
                $('.flag_checkbox').each(function () {
                    if (this.checked) {
                        sList += $(this).val() + "#";
                        validateData = true;
                    }
                });
                $('.order_ids').val(sList);
            }
            if (validateData == false) {
                alert("Please select atleast one order.");
                return false;
            } else {
                if (confirm('Please confirm, really you want to assign these order to the Shipper')) {
                    $("#flag_submit").submit();
                }
            }
        });
    });
</script>

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

        var all = $('.all').DataTable({
            responsive: true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[1, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.assign_shipper = "all";
                    d.financial_status = $('#all #paymentStatus').val();
                    d.fulfillment_status = $('#all #fulfillmentstatus').val();
                    d.order_status = $('#all #orderStatus').val();
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'assign_shipper'},
                {mData: 'order_number'},
                {mData: 'created_at'},
                {mData: 'email'},
                {mData: 'cust_fname'},
                {mData: 'order_value'},
                {mData: 'financial_status'},
                {mData: 'items'},
            ],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']},
            ],
            "language": {
                "zeroRecords": "No order available",
                "paginate": {
                    "previous": "< ",
                    "next": " >"
                }
            }
        });


        var open = $('.open').DataTable({
            responsive: true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[1, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.assign_shipper = 0;
                    d.financial_status = $('#open #paymentStatus').val();
                    d.fulfillment_status = $('#open #fulfillmentstatus').val();
                    d.order_status = $('#open #orderStatus').val();
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'assign_shipper'},
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


        var sourcedandpending = $('.sourcedandpending').DataTable({
            responsive: true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[1, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.assign_shipper = 1;
                    d.financial_status = $('#sourcedandpending #paymentStatus').val();
                    d.fulfillment_status = $('#sourcedandpending #fulfillmentstatus').val();
                    d.order_status = $('#sourcedandpending #orderStatus').val();
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'assign_shipper'},
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


        var fulfilled = $('.fulfilled').DataTable({
            responsive: true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[1, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.assign_shipper = 1;
                    d.financial_status = $('#fulfilled #paymentStatus').val();
                    d.fulfillment_status = "fulfilled";
                    d.order_status = $('#fulfilled #orderStatus').val();
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'assign_shipper'},
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

        $('#all #paymentStatus, #all #orderStatus, #all #fulfillmentstatus').on('change', function (e) {
            all.draw();
        });

        $('#all #searchbox').keyup(function () {
            all.search($(this).val()).draw();
        });

        $('#open #paymentStatus, #open #orderStatus, #open #fulfillmentstatus').on('change', function (e) {
            open.draw();
        });

        $('#open #searchbox').keyup(function () {
            open.search($(this).val()).draw();
        });

        $('#sourcedandpending #paymentStatus, #sourcedandpending #orderStatus, #sourcedandpending #fulfillmentstatus').on('change', function (e) {
            sourcedandpending.draw();
        });

        $('#sourcedandpending #searchbox').keyup(function () {
            sourcedandpending.search($(this).val()).draw();
        });

        $('#fulfilled #paymentStatus, #fulfilled #orderStatus, #fulfilled #fulfillmentstatus').on('change', function (e) {
            fulfilled.draw();
        });

        $('#fulfilled #searchbox').keyup(function () {
            fulfilled.search($(this).val()).draw();
        });



        $('#all_tab').click(function () {
            all.draw();
        });
        $('#open_tab').click(function () {
            open.draw();
        });
        $('#sourcedandpending_tab').click(function () {
            sourcedandpending.draw();
        });
        $('#fulfilled_tab').click(function () {
            fulfilled.draw();
        });

        var previous_export = $('.previous_export').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[1, "desc"]],
            "ajax": {
                url: "{{ url('showcsvlogs') }}",
                data: function (d) {
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'log_file'},
                {mData: 'created_at'},
            ],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']}
            ],
            "language": {
                "zeroRecords": "No csv available",
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
    });
</script>
@endsection