@if(!$store_products->isEmpty())
<table class="table table-hover products-table">
    <tr>
        <th width='10%'>Sourced</th>
        <th width='30%'>Product Detail</th>
        <th width='60%'>&nbsp;</th>
        @foreach($store_products as $store_product)
    <tr>
        <td style="text-align: center;">
            <input type="checkbox" class="flag_checkbox" style="width: 20px;height: 20px;" name="product_status[]" data-id="flagData" value="{{ $store_product->id }}" />
        </td>
        <td>
            <div class="box box-warning" style="border-top: 0px solid #d2d6de;">
                <div class="box-body">
                    <strong>{{ $store_product->title }}</strong>
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
            <div class="row product_item_{{ $store_product->id }}" style="display: none;">
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
                    <label for="">what is your average cost per unit?($)</label>
                    {!! Form::number('cost_per_unit['.$store_product->id.']', null, array('placeholder' => '', 'step' => 'any', 'class' => 'form-control cost_per_unit_'.$store_product->id, 'min' => '0')) !!}
                </div>
            </div>
        </td>
    </tr>
    @endforeach
</table>
@else
<div class="center">Your store does not appear to have any products. Please add any product and try again.</div>
@endif