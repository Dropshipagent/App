@extends('layouts.app')
@section('title', 'Tracking Info')
@section('main-content')


<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="col-md-12 heading_sections">
        <!-- <h1 class="heading">Dropship <span style="color: #FFF;">Agent</span></h1> -->
        <img src="{{ asset('img/dropship.png') }}" class="logo-dropship">
        <p class="subheading">Tracking</p>
    </div>
    <div class="col-md-12">
        <div class="table_design table-responsive">
            <div class="datatablefilters">
                <div class="searchfilter">                            
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" id="searchbox" class="form-control" placeholder="Search">
                    </div>                            
                </div>

            </div>
            <table class="table table-striped table-bordered datatable tracking">
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