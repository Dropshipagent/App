@extends('admin.layouts.app')
@section('title', 'Product Listing')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Store [{{ $store_domain }}] Products
        <small>list of all products</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('/admin/users') }}">Users</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="nav-tabs-custom" style="position: relative">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#requested" data-toggle="tab" id="requested_tab">Requested</a></li>
            <li><a href="#admin_approved" data-toggle="tab" id="admin_approved_tab">Admin Approved</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="requested">
                <table class="table table-striped table-bordered datatable requested">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Product Price</th>
                            <th>Product Image</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>


            <div class="tab-pane" id="admin_approved">
                <table class="table table-striped table-bordered datatable admin_approved">
                    <thead> 
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Product Price</th>
                            <th>Product Image</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>          
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->

<!-- Modal -->
<div id="acceptProductsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Product</h4>
            </div>
            <div class="modal-body clearfix">
                <div class="productsResult">
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        function initiateTable(tableId, productStatus, productAction) {
            var table = $('.' + tableId).DataTable({
                responsive: true,
                "bProcessing": true,
                "serverSide": true,
                "ordering": true,
                "order": [[0, "desc"]],
                "ajax": {
                    url: "",
                    data: function (d) {
                        d.product_status = productStatus;
                        d.product_action = productAction;
                    },
                    error: function (error) {
                        console.log(error);
                        alert('Something went wrong');
                    }
                },
                "aoColumns": [
                    {mData: 'product_id', "bSortable": true},
                    {mData: 'product_name', "bSortable": false},
                    {mData: 'product_price', "bSortable": false},
                    {mData: 'product_image', "bSortable": false},
                    {mData: 'product_action', "bSortable": false},
                ],
                "destroy": true,
                "language": {
                    "zeroRecords": "No product available",
                    "paginate": {
                        "previous": "< ",
                        "next": " >"
                    }
                }
            });
        }
        initiateTable("requested", "1", "1");
        $('#requested_tab').click(function () {
            initiateTable("requested", "1", "1");
        });
        $('#admin_approved_tab').click(function () {
            initiateTable("admin_approved", "2", "0");
        });

        //code ask for price after accept product
        $(document).on('click', '.btnAcceptProduct', function (e) {
            var productEditID = $(this).data('id');
            // show Modal
            $.ajax({
                url: productEditID,
                type: "GET",
                dataType: "html",
                success: function (data) {
                    $('.productsResult').html(data);
                },
                error: function (xhr, status) {
                    alert("Sorry, there was a problem!");
                },
                complete: function (xhr, status) {
                    $('#acceptProductsModal').modal('show');
                }
            });
        });

        //code for final approval of a product
        $(document).on('click', '.reject_product', function (e) {
            var productID = $(this).data('id');
            var r = confirm("Are you sure?");
            if (r == true) {
                $.ajax({
                    type: 'POST',
                    url: '{{ url("admin/products/product-status") }}',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {"product_id": productID},
                    success: function (data) {
                        if (data.data.success) {
                            alert("Product rejected successfully!");
                            initiateTable("requested", "1", "1");
                        }
                    }
                });
            }
        });
    });
</script>
@endsection