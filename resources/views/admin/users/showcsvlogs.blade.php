@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<link rel="stylesheet" href="{{ asset('admin/bower_components/jquery-ui/themes/base/jquery-ui.css') }}">
<!-- Content Header (Page header) -->
@include('admin.layouts.header-tabs')
<section class="content-header">
    <h1>
        Csv Logs
        <small>list of all csv logs</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('/admin/users') }}">Users</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="">
        <div class="table-responsive">
            <div class="datatablefilters">
                <div class="searchfilter">                            
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" id="searchbox" class="form-control" placeholder="Search">
                    </div>                            
                </div>

            </div>
            <table class="table table-striped table-bordered datatable csv_logs">
                <thead> 
                    <tr>
                        <th>Store Domain</th>
                        <th>Order Number</th>
                        <th>Created at</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function () {

        $('#searchbox').keyup(function () {
            csvLogs.search($(this).val()).draw();
        });

        var csvLogs = $('.csv_logs').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[2, "desc"]],
            "ajax": {
                url: "",
                data: function (d) {
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong');
                }
            },
            "aoColumns": [
                {mData: 'store_domain'},
                {mData: 'csv_file_name'},
                {mData: 'created_at'}
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
    })
</script>
@endsection