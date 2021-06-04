<!-- Main content -->
{!! Form::model($product, ['route' => ['products.update', $product->id], 'id'=>'confirmReqForm','files'=>true,'method'=>'PATCH']) !!}
@if($product->product_status < 2)
{!! Form::hidden('product_status',2) !!}
@endif
{!! Form::hidden('current_product_status',$product->product_status) !!}
{!! Form::hidden('store_domain') !!}
{!! Form::hidden('title') !!}
<div class="nav-tabs-custom" style="position: relative">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#product_quick_info" data-toggle="tab" id="product_quick_info_tab">Product</a></li>
        <li><a href="#product_variants" data-toggle="tab" id="product_variants_tab">Variants</a></li>
        <li><a href="#product_info" data-toggle="tab" id="product_info_tab">Product Info</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active table-responsive" id="product_quick_info">
            <table class="table table-hover">
                <tr>
                    <th>Product Name</th>
                    <th>Product Image</th>
                </tr>
                <tr>
                    <td>{{ $product->title }}</td>
                    <td>
                        <?php
                        $imageArr = json_decode($product->image);
                        if (!empty($imageArr->src)) {
                            echo '<img src="' . $imageArr->src . '" width="200" />';
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="tab-pane" id="product_variants">
            <!-- text input -->
            <div class="form-group table-responsive">
                <table class="table table-hover">
                    <tr>
                        <th>Item Name</th>
                        <th>Price($)</th>
                        <th>Base Price($)</th>
                    </tr>
                    <?php
                    $basePriceArr = json_decode($product->base_price, true);
                    $adminComisonPriceArr = json_decode($product->admin_commission, true);
                    $variantsArr = json_decode($product->variants);
                    foreach ($variantsArr as $variant) {
                        echo Form::hidden('variant_id[]', $variant->id);
                        echo Form::hidden('variant_price[]', $variant->price);
                        echo '<tr><td>' . $variant->title . '</td><td>' . $variant->price . '</td><td>' . Form::number('base_price_' . $variant->id, $basePriceArr[$variant->id], array('step' => 'any', 'min' => '1', 'max' => $variant->price, 'required' => 'required', 'class' => 'form-control')) . '</td></tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="product_info">
            <div class="row product_item_{{ $product->id }}">
                <div class="col-md-12 form-group">
                    What is your aliexpress product URL?
                    <a class="" target="_blank" href="{{$product->aliexpress_url}}">View Product &RightTriangle;</a>
                </div>
                <div class="col-md-12 form-group">
                    How many orders per day?
                    <label for="">{{$product->orders_per_day}}</label>
                </div>
                <div class="col-md-12 form-group">
                    What variants do you sell?(xx,xx)
                    <label for="">{{$product->variants_you_sell}}</label>
                </div>
                <div class="col-md-12 form-group">
                    To what countries do you ship?(xx,xx)
                    <label for="">{{$product->countries_you_ship}}</label>
                </div>
                <div class="col-md-12 form-group">
                    What is your average cost per unit?($)
                    <label for="">{{$product->cost_per_unit}}</label>
                </div>
                <div class="col-md-12 form-group">
                    Shipping Time?
                    <label for="">{!! Form::text('shipping_time', $product->shipping_time, array('placeholder' => '','class' => 'form-control')) !!}</label>
                </div>
            </div>
        </div>
    </div>
    <!-- /.tab-content -->
</div>

<div class="form-group">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" id="submitBtn" class="btn btn-primary btnConfirmReq">Submit</button>
</div>
{!! Form::close() !!}
<!-- /.content -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.btnConfirmReq').on('click', function () {
            var r = confirm("Are you sure you want to confirm this request?");
            if (r == true) {
                $("#confirmReqForm").submit();
            }
        });
    });
</script>