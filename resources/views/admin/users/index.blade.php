@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<link rel="stylesheet" href="{{ asset('admin/bower_components/jquery-ui/themes/base/jquery-ui.css') }}">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Users List
        <small>list of all users</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('/admin/users') }}">Users</a></li>
    </ol>
</section>


<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#pending_req" id="pending_req_tab" data-toggle="tab">Pending requests</a></li>
                    <li><a href="#app_and_unpaid" id="app_and_unpaid_tab" data-toggle="tab">Approved and Unpaid</a></li>
                    <li><a href="#app_and_paid" id="app_and_paid_tab" data-toggle="tab">Approved and Paid</a></li>
                    <li><a href="#shippers" id="shippers_tab" data-toggle="tab">Shippers</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="pending_req">
                        <table id="pending_reqData" class="table table-hover table-striped table-bordered datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane" id="app_and_unpaid">
                        <table id="app_and_unpaidData" class="table table-hover table-striped table-bordered datatable">
                            <thead>    
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane" id="app_and_paid">
                        <table id="app_and_paidData" class="table table-hover table-striped table-bordered datatable">
                            <thead>    
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane" id="shippers">
                        <div class="box-header with-border">
                            <h3 class="box-title">Shippers</h3>

                            <div class="box-tools pull-right">
                                <a href="{{ url('/admin/users/create') }}" class="btn btn-block btn-danger btn-sm">Add New Shipper</a>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-body table-responsive no-padding">
                                <table id="shipperData" class="table table-hover table-striped table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Created</th>
                                            <th>Updated</th>
                                            <th>Mapped Store</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<!-- Modal -->
<div id="shipperModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Shipper</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    {!! Form::open(array('url' => 'admin/users/email_csv','id' => 'assignShipperForm','files'=>true,'method'=>'POST')) !!}
                    {!! Form::hidden('store_id', null, array('class' => 'store_id')) !!}
                    {!! Form::hidden('shipper_id', null, array('class' => 'shipper_id')) !!}
                    {!! Form::hidden('store_domain', null, array('class' => 'store_domain')) !!}
                    <div class="col-md-8">
                        <div class="form-group">
                            <strong>Select Shipper by Tag:</strong>
                            <input id="tags" class="form-control" required="required">
                        </div>    
                    </div>
                    <div class="col-md-4">
                        <br>
                        <button type="button" id="" class="btn btn-primary asnShipperBtn">Submit</button> 
                    </div>
                    {!! Form::close() !!}
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
        $(document).on('click', '.assign_shipper_btn', function () {
            $('.store_id').val($(this).data("id"));
            $('.store_domain').val($(this).data("val"));
            // show Modal
            $('#shipperModal').modal('show');
        });
        //auto complete textbox jquery
        $("#tags").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ url('admin/users/search_users') }}",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function (event, ui) {
                $(".shipper_id").val(ui.item.id);
                //log("Selected: " + ui.item.value + " aka " + ui.item.id);
            }
        });
        $(document).on('click', '.asnShipperBtn', function () {
            var shipperID = $(".shipper_id").val();
            if (shipperID != '') {
                $("#assignShipperForm").submit();
            } else {
                alert("Please select shipper from the autocomplete dropdown!");
            }
        });
    });
</script>
<style type="text/css">
    .ui-autocomplete { z-index:2147483647; }

</style>
<!-- /.content -->
<script type="text/javascript">
    $(document).ready(function () {
        var pending_reqData = $('#pending_reqData').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[0, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.tab = "Pending_requests";
                    d.role = "2";
                    d.status = "0";
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'id'},
                {mData: 'name'},
                {mData: 'username'},
                {mData: 'email'},
                {mData: 'created_at'},
                {mData: 'updated_at'},
                {mData: 'action'},
            ],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 10001, targets: 3},
                {responsivePriority: 2, targets: -2}
            ],
            "language": {
                "zeroRecords": "No user available",
                "paginate": {
                    "previous": "< ",
                    "next": " >"
                }
            }
        });
        var app_and_unpaidData = $('#app_and_unpaidData').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[0, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.tab = "Approved_and_Unpaid";
                    d.role = "2";
                    d.status = "1";
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'id'},
                {mData: 'name'},
                {mData: 'username'},
                {mData: 'email'},
                {mData: 'created_at'},
                {mData: 'updated_at'},
                {mData: 'action'},
            ],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 10001, targets: 3},
                {responsivePriority: 2, targets: -2}
            ],
            "language": {
                "zeroRecords": "No user available",
                "paginate": {
                    "previous": "< ",
                    "next": " >"
                }
            }
        });
        var app_and_paidData = $('#app_and_paidData').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[0, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.tab = "Approved_and_Paid";
                    d.role = "2";
                    d.status = "2";
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'id'},
                {mData: 'name'},
                {mData: 'username'},
                {mData: 'email'},
                {mData: 'created_at'},
                {mData: 'updated_at'},
                {mData: 'action'},
            ],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 10001, targets: 3},
                {responsivePriority: 2, targets: -2}
            ],
            "language": {
                "zeroRecords": "No user available",
                "paginate": {
                    "previous": "< ",
                    "next": " >"
                }
            }
        });
        var shipperData = $('#shipperData').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[0, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                    d.tab = "Shippers";
                    d.role = "3";
                    d.status = null;
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'id'},
                {mData: 'name'},
                {mData: 'username'},
                {mData: 'email'},
                {mData: 'created_at'},
                {mData: 'updated_at'},
                {mData: 'action'},
            ],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 10001, targets: 3},
                {responsivePriority: 2, targets: -2}
            ],
            "language": {
                "zeroRecords": "No user available",
                "paginate": {
                    "previous": "< ",
                    "next": " >"
                }
            }
        });
        $('#pending_req_tab').click(function () {
            pending_reqData.draw();
        });
        $('#app_and_unpaid_tab').click(function () {
            app_and_unpaidData.draw();
        });
        $('#app_and_paid_tab').click(function () {
            app_and_paidData.draw();
        });
        $('#shippers_tab').click(function () {
            shipperData.draw();
        });
        //$('#pending_reqData').DataTable();
        //$('#app_and_unpaidData').DataTable();
        //$('#app_and_paidData').DataTable();
        //$('#shipperData').DataTable();

        //code for final approval of a product
        $(document).on('click', '.accept_user', function (e) {
            var userID = $(this).data('id');
            var r = confirm("Are you sure?");
            if (r == true) {
                $.ajax({
                    type: 'POST',
                    url: '{{ url("admin/users/user-status") }}',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {"user_id": userID},
                    success: function (data) {
                        if (data.data.success) {
                            alert(data.data.message);
                            location.reload();
                        } else {
                            alert(data.data.message);
                        }
                    }
                });
            }
        });
    });
</script>
@endsection