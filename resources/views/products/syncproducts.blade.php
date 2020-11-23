<!-- Main content -->
<section class="content">
    {!! Form::open(array('url' => 'storeproducts/productflag','id' => 'flagRecForm','files'=>true,'method'=>'POST')) !!}
    <!-- Default box -->
    <div class="box">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover products-table">
                <tr>
                    <th>Sourced</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Product Image</th>
                    @foreach($store_products as $store_product)
                <tr>
                    <td style="text-align: center;">
                        <input type="checkbox" class="flag_checkbox" style="width: 20px;height: 20px;" name="product_status[]" data-id="flagData" value="{{ $store_product->id }}" />
                    </td>
                    <td>{{ $store_product->title }}</td>
                    <td>
                        <div class="box box-warning" style="border-top: 0px solid #d2d6de;">
                            <div class="box-body">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                    </tr>
                                    <?php
                                    $variantsArr = json_decode($store_product->variants);
                                    $basePriceArr = json_decode($store_product->base_price, true);
                                    foreach ($variantsArr as $variant) {
                                        //print_r($basePriceArr); die;
                                        if (isset($basePriceArr[$variant->id])) {
                                            $basePrice = number_format($basePriceArr[$variant->id], 2);
                                        } else {
                                            $basePrice = 0;
                                        }
                                        echo '<tr><td>' . $variant->title . '</td><td>' . $variant->price . '</td></tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </td>
                    <td>
                        <?php
                        $imageArr = json_decode($store_product->image);
                        if ($imageArr) {
                            echo '<img src="' . $imageArr->src . '" height="100px"  width="100px" />';
                        }
                        ?>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="box-footer">
        <div class="form-group">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="" class="btn btn-danger sendFlagRec">Send Request</button> 
        </div>
    </div>
    <!-- /.box-footer-->
    {!! Form::close() !!}
</section>
<!-- /.content -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.sendFlagRec').on('click', function () {
            var validateData = false;
            $('.flag_checkbox').each(function () {
                if (this.checked) {
                    validateData = true;
                }
            });
            if (validateData == false) {
                alert("Please select atleast one product.");
                return false;
            } else {
                var r = confirm("Are you sure?");
                if (r == true) {
                    $("#flagRecForm").submit();
                }
            }
        });
    });
</script>