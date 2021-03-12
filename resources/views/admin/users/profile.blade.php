@extends('admin.layouts.app')
@section('title', 'Edit User')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Profile
        <small></small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="register-box-body">
                <div class="box box-primary box-primary-box">
                    <!-- form start -->
                    <div class="box-body">
                        <div class="col-md-6 form-group">
                            <label for="">Store Owner Name:</label>
                            <br>
                            {{$user->name}}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Email:</label>
                            <br>
                            {{$user->email}}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Currency:</label>
                            <br>
                            {{$user->currency_code}}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="phone">Contact Number:</label>
                            <br>
                            {{$user->phone}}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">City:</label>
                            <br>
                            {{$user->city}}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">State:</label>
                            <br>
                            {{$user->state}}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Country:</label>
                            <br>
                            {{$user->country}}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Zip Code:</label>
                            <br>
                            {{$user->zip_code}}
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Billing Address</label>
                            <br>
                            {{$user->billing_address}}
                        </div>
                        <table class="table table-hover products-table">
                            <tr>
                                <th width="20%">Product Name</th>
                                <th width="25%">Product Price</th>
                                <th width="15%">Product Image</th>
                                <th width="40%">Detail</th>
                            </tr>
                            @foreach($products as $store_product)
                            <tr>
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
                                <td>
                                    <div class="row product_item_{{ $store_product->id }}">
                                        <div class="col-md-12 form-group">
                                            What is your aliexpress product URL?
                                            <label for="">{{$store_product->aliexpress_url}}</label>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            How many orders per day?
                                            <label for="">{{$store_product->orders_per_day}}</label>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            What variants do you sell?(xx,xx)
                                            <label for="">{{$store_product->variants_you_sell}}</label>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            To what countries do you ship?(xx,xx)
                                            <label for="">{{$store_product->countries_you_ship}}</label>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            What is your average cost per unit?($)
                                            <label for="">{{$store_product->cost_per_unit}}</label>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            Shipping Time?
                                            <label for="">{{$store_product->shipping_time}}</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!-- /.form-box -->
        </div>
    </div>

</section>
<!-- /.content -->
@endsection