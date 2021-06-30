@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
@include('admin.layouts.header-tabs')
<section class="content-header">
    <h1>
        Tracking Lists
        <small>list of all tracking info.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/supplier/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('/supplier/stores') }}">Mapped Stores</a></li>
    </ol>
</section>
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
            <table class="table table-striped table-bordered datatable tracking" data-page-length='50'>
                <thead> 
                    <tr>
                        <th>Invoice-ID</th>
                        <th>Store Domain</th>
                        <th>Order Number</th>
                        <th>Tracking Number</th>
                        <th>Tracking Url</th>
                        <th>Tracking Company</th>
                        <th>Uploaded</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>
</section>
<!-- Main content -->
<!-- /.content -->
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function () {

        $('#searchbox').keyup(function () {
            tracking.search($(this).val()).draw();
        });

        var tracking = $('.tracking').DataTable({
            "responsive": true,
            "bProcessing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[0, "desc"]],
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
                {mData: 'id'},
                {mData: 'store_domain'},
                {mData: 'order_number'},
                {mData: 'tracking_number'},
                {mData: 'tracking_url'},
                {mData: 'tracking_company'},
                {mData: 'created_at'}
            ],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']}
            ],
            "language": {
                "zeroRecords": "No tracking available",
                "paginate": {
                    "previous": "< ",
                    "next": " >"
                }
            }
        });
    })
</script>
@endsection