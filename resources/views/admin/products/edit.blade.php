<!-- Main content -->
{!! Form::model($product, ['route' => ['products.update', $product->id], 'id'=>'confirmReqForm','files'=>true,'method'=>'PATCH']) !!}
{!! Form::hidden('product_status',2) !!}
{!! Form::hidden('store_domain') !!}
{!! Form::hidden('title') !!}
<table class="table table-hover">
    <tr>
        <th>Product Name</th>
        <th>Product Image</th>
        <th>Product Price</th>
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
        <td>
            <div class="box box-warning">
                <div class="box-body">
                    <table class="table table-hover">
                        <tr>
                            <th>Item Name</th>
                            <th>Price</th>
                        </tr>
                        <?php
                        $variantsArr = json_decode($product->variants);
                        foreach ($variantsArr as $variant) {
                            echo '<tr><td>' . $variant->title . '</td><td>' . $variant->price . '</td></tr>';
                        }
                        ?>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </td>
    </tr>
</table>
<!-- text input -->
<div class="form-group">
    <div class="box box-warning">
        <div class="box-body">
            <table class="table table-hover">
                <tr>
                    <th>Item Name</th>
                    <th>Base Price($)</th>
                </tr>
                <?php
                $basePriceArr = json_decode($product->base_price, true);
                $adminComisonPriceArr = json_decode($product->admin_commission, true);
                foreach ($variantsArr as $variant) {
                    echo Form::hidden('variant_id[]', $variant->id);
                    echo Form::hidden('variant_price[]', $variant->price);
                    echo '<tr><td>' . $variant->title . '</td><td>' . Form::number('base_price_' . $variant->id, $basePriceArr[$variant->id], array('step' => 'any', 'min' => '1', 'max' => $variant->price, 'required' => 'required', 'class' => 'form-control')) . '</td></tr>';
                }
                ?>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
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
            var r = confirm("Are you sure you want to confirm this request? You won't be able to change the price, if you confirm!");
            if (r == true) {
                $("#confirmReqForm").submit();
            }
        });
    });
</script>