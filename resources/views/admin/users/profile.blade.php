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
    <div class="register-box" style="margin-top: 0px; width: 600px;">
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
                    <?php
                    $productsDataArr = json_decode($user->products_data, true);
                    ?>
                    @foreach($productsDataArr as $key => $val)
                    <div class="fieldGroup">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="col-md-12 form-group">
                                    <label for="">Product Title</label>
                                    <br>
                                    {{$products[$key]->title}}
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="">What is your aliexpress product URL?</label>
                                    <br>
                                    {{$val['aliexpress_url']}}
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="">How many orders per day?</label>
                                    <br>
                                    {{$val['orders_per_day']}}
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="">What variants do you sell?(xx,xx)</label>
                                    <br>
                                    {{$val['variants_you_sell']}}
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="">To what countries do you ship?(xx,xx)</label>
                                    <br>
                                    {{$val['countries_you_ship']}}
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="">what is your average cost per unit?($)</label>
                                    <br>
                                    {{$val['cost_per_unit']}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div><!-- /.box-body -->
            </div>
        </div>
        <!-- /.form-box -->
    </div>

</section>
<!-- /.content -->
@endsection