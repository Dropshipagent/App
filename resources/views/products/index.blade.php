@extends('layouts.app')
@section('title', 'Product Listing')
@section('main-content')

<!-- Main content -->
<section class="content product_page">
    <div class="col-md-12 heading_sections">
        <!-- <h1 class="heading">Dropship <span style="color: #FFF;">Agent</span></h1> -->
        <img src="{{ asset('img/dropship.png') }}" class="logo-dropship">
        <p class="subheading"></p>
    </div>
    <!-- Default box -->
    @if(app('request')->input('pdstatus') == 1)
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default credit-card-box">
                <div class="panel-body">
                    <div>
                        <h3 class="heading">You have been accepted!</h3>
                        <p>We have approved your request. Please see the quoted price below. You can complete the sign up by clicking the "Proceed" button.</p>
                        <div class="row">
                            <div class="col-md-5">
                                <p class="accept-order"><img class="side-bar-icon" src="{{asset('img/receipt-dollar.png')}}" alt="side-bar-icon"> 
                                    <?php
                                    $totalPrice = 0.00;
                                    if ($adminApprovedLastProduct) {
                                        $variantsArr = json_decode($adminApprovedLastProduct->variants);
                                        $basePriceArr = json_decode($adminApprovedLastProduct->base_price, true);
                                        $adminComisonPriceArr = json_decode($adminApprovedLastProduct->admin_commission, true);
                                        $totalVariants = count($variantsArr);
                                        $variantsDataArray = [];
                                        foreach ($variantsArr as $variant) {
                                            //print_r($basePriceArr); die;
                                            if (isset($basePriceArr[$variant->id])) {
                                                $basePrice = $basePriceArr[$variant->id];
                                                $adminComisonPrice = $adminComisonPriceArr[$variant->id];
                                            } else {
                                                $basePrice = 0;
                                                $adminComisonPrice = 0;
                                            }
                                            $priceSubtotal = number_format(($basePrice + $adminComisonPrice), 2);
                                            $variantsDataArray[] = [
                                                'title' => $variant->title,
                                                'price' => $priceSubtotal
                                            ];

                                            $totalPrice += $priceSubtotal;
                                        }
                                    }
                                    if ($totalVariants == 1) {
                                        echo 'Sourced Price: $' . $totalPrice;
                                    } else {
                                        echo 'Sourced Price: See price below';
                                    }
                                    ?>
                                    @if($totalVariants == -1)
                                <table class="table table-hover">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Sourced Price($)</th>
                                    </tr>
                                    <?php
                                    foreach ($variantsDataArray as $key => $val) {
                                        echo '<tr><td>' . $val['title'] . '</td><td>' . $val['price'] . '</td></tr>';
                                    }
                                    ?>
                                </table>
                                @endif
                                </p>
                            </div>
                            <div class="col-md-5">
                                <p class="accept-order"><img class="side-bar-icon" src="{{asset('img/shipping-icon.png')}}" alt="side-bar-icon"> Shipping Time: <?php echo ($adminApprovedLastProduct && $adminApprovedLastProduct->shipping_time) ? $adminApprovedLastProduct->shipping_time : "" ?></p>
                            </div>
                        </div>
                        <p>We look forward to having you onboard.</p>
                        <p>Regards,<br>Dropship Agent Team</p>
                    </div>
                </div>
            </div>        
        </div>
    </div>
    @endif

    <!-- Default box -->
    <div class="nav-tabs-custom" style="position: relative">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#approved" data-toggle="tab" id="approved_tab">Accepted</a></li>
            <li><a href="#admin_approved" data-toggle="tab" id="admin_approved_tab">Admin Approved</a></li>
            <li><a href="#requested" data-toggle="tab" id="requested_tab">Requested</a></li>
        </ul>
        <div class="pull-right" style="position: absolute; right:40px; top:30px;">
            <a href="javascript:void(0)" class="btn btn-block btn-danger btn-sm btnSyncProducts">Sync Products</a>
        </div>
        <div class="tab-content">
            <div class="tab-pane active table-responsive" id="approved">
                <table class="table table-striped table-bordered datatable approved">
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Shipping Time</th>
                            <th>Product Price</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="tab-pane table-responsive" id="admin_approved">
                <table class="table table-striped table-bordered datatable admin_approved">
                    <thead> 
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Shipping Time</th>
                            <th>Product Price</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="tab-pane table-responsive" id="requested">
                <table class="table table-striped table-bordered datatable requested">
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Shipping Time</th>
                            <th>Product Price</th>
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
<div id="syncProductsModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sync Products</h4>
            </div>
            <div class="modal-body clearfix">
                <div class="productsResult">
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        //show modal popup jquery
        $(".btnSyncProducts").click(function () {
            // show Modal
            $.ajax({
                url: "{{ url('storeproducts/syncproducts', $store_domain) }}",
                type: "GET",
                dataType: "html",
                success: function (data) {
                    $('.productsResult').html(data);
                },
                error: function (xhr, status) {
                    alert("Sorry, there was a problem!");
                },
                complete: function (xhr, status) {
                    //$('#showresults').slideDown('slow')
                    $('#syncProductsModal').modal('show');
                }
            });
        });
    });
</script>
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
                "order": [[1, "asc"]],
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
                    {mData: 'product_image', "bSortable": false},
                    {mData: 'product_name', "bSortable": true},
                    {mData: 'shipping_time', "bSortable": false},
                    {mData: 'product_price', "bSortable": false},
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
        initiateTable("approved", "3", "0");
        $('#requested_tab').click(function () {
            initiateTable("requested", "1", "0");
        });
        $('#admin_approved_tab').click(function () {
            initiateTable("admin_approved", "2", "1");
        });
        $('#approved_tab').click(function () {
            initiateTable("approved", "3", "0");
        });

        //code for final approval of a product
        $(document).on('click', '.accept_product', function (e) {
            var productID = $(this).data('id');
            var r = confirm("Are you sure?");
            if (r == true) {
                $.ajax({
                    type: 'POST',
                    url: '{{ url("storeproducts/product-status") }}',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {"product_id": productID},
                    success: function (data) {
                        if (data.data.success) {
                            jQuery('.close').trigger("click");
                            showAlertMessage("Product accepted successfully!");
                            initiateTable("admin_approved", "2", "1");
                        }
                    }
                });
            }
        });
        var adminAPPtab = "{{ app('request')->input('pdstatus') }}";
        if (adminAPPtab == 1) {
            $("#admin_approved_tab").trigger("click");
        }
    });
</script>
@endsection