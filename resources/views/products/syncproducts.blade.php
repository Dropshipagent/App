<!-- Main content -->
<section class="content">
    {!! Form::open(array('url' => 'storeproducts/productflag','id' => 'flagRecForm','files'=>true,'method'=>'POST')) !!}
    <!-- Default box -->
    <div class="">
        <div class="table-responsive">
            <table class="table table-hover products-table">
                <tr>
                    <th>Sourced</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Product Image</th>
                </tr>
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
                <tr class="row product_item_{{ $store_product->id }}" style="display: none;">
                    <td colspan="4">
                        <div>
                            <div class="col-md-12 form-group">
                                <label for="">What is your aliexpress product URL?</label>
                                {!! Form::text('aliexpress_url['.$store_product->id.']', null, array('placeholder' => '','class' => 'form-control aliexpress_url_'.$store_product->id)) !!}
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">How many orders per day?</label>
                                {!! Form::number('orders_per_day['.$store_product->id.']', null, array('placeholder' => '','class' => 'form-control orders_per_day_'.$store_product->id,  'min' => '0')) !!}
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">What variants do you sell?(xx,xx)</label>
                                {!! Form::text('variants_you_sell['.$store_product->id.']', null, array('placeholder' => '','class' => 'form-control variants_you_sell_'.$store_product->id)) !!}
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">To what countries do you ship?(xx,xx)</label>
                                {!! Form::text('countries_you_ship['.$store_product->id.']', null, array('placeholder' => '','class' => 'form-control countries_you_ship_'.$store_product->id)) !!}
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">What is your average cost per unit?($)</label>
                                {!! Form::number('cost_per_unit['.$store_product->id.']', null, array('placeholder' => '', 'step' => 'any', 'class' => 'form-control cost_per_unit_'.$store_product->id, 'min' => '0')) !!}
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Shipping time?</label>
                                {!! Form::text('shipping_time['.$store_product->id.']', null, array('placeholder' => '','class' => 'form-control shipping_time_'.$store_product->id)) !!}
                            </div>
                        </div>
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
                var requiredField = false;
                var checkValidURL = false;
                var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                        '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                        '(\\?[;&a-z\\d%_.~+=-]*)?', 'i'); // fragment locator
                $('[required]').each(function () {
                    if ($(this).val() == '') {
                        requiredField = true;
                    }
                });
                $('.check_valid_url').each(function () {
                    if (pattern.test($(this).val()) == false) {
                        checkValidURL = true;
                    }
                });
                if (checkValidURL == true) {
                    alert("Please enter valid URL in product URL");
                    return false;
                }
                if (requiredField == true) {
                    alert("Please fill all required fields");
                } else {
                    var r = confirm("Are you sure?");
                    if (r == true) {
                        $("#flagRecForm").submit();
                    }
                }
            }
        });

        //action on checkbox check or uncheck
        $(document).on('change', '.flag_checkbox', function (e) {
            var productID = this.value;
            if (this.checked) {
                $('.aliexpress_url_' + productID).attr("required", true);
                $('.aliexpress_url_' + productID).addClass("check_valid_url");
                $('.orders_per_day_' + productID).attr("required", true);
                $('.product_item_' + productID).show();
            } else {
                $('.aliexpress_url_' + productID).attr("required", false);
                $('.aliexpress_url_' + productID).removeClass("check_valid_url");
                $('.orders_per_day_' + productID).attr("required", false);
                $('.product_item_' + productID).hide();
            }
        });
    });
</script>