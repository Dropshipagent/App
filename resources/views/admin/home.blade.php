@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('main-content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Dashboard
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-body">
            <div class="row">
<!--                <div class="col-lg-3 col-md-6 col-sm-6">
                     small box 
                    <div class="small-box orders_bg">
                        <div class="dislpay_flex_box">
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <div class="inner">
                                <h3>{{ $orders }}</h3>
                                <p>Total Orders</p>
                            </div>
                        </div>
                        <a href="{{ url('/admin/orders') }}" class="small-box-footer">More info</a>
                    </div>
                </div>-->
                <!-- ./col -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <!-- small box -->
                    <div class="small-box">
                        <div class="dislpay_flex_box">                            
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <div class="inner">
                                <h3>{{ $suppliers }}</h3>

                                <p>Total Suppliers</p>
                            </div>
                        </div>
                        <a href="{{ url('/admin/users#suppliers') }}" class="small-box-footer">More info</a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <!-- small box -->
                    <div class="small-box source-product_store">
                        <div class="dislpay_flex_box">
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <div class="inner">
                                <h3>{{ $stores }}</h3>

                                <p>Total Stores</p>
                            </div>
                        </div>
                        <a href="{{ url('/admin/users#app_and_paid') }}" class="small-box-footer">More info</a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <!-- small box -->
                    <div class="small-box invoices_bg">
                        <div class="dislpay_flex_box">                            
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <div class="inner">
                                <h3>{{ $storeInvoices }}</h3>

                                <p>Total Invoices</p>
                            </div>
                        </div>
                        <a href="{{ url('/admin/showinvoiceslog') }}" class="small-box-footer">More info</a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <!-- small box -->
                    <div class="small-box traking_bg">
                        <div class="dislpay_flex_box">
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <div class="inner">
                                <h3>{{ $uploadedTrackings }}</h3>

                                <p>Total Tracking Info</p>
                            </div>
                        </div>
                        <a href="{{ url('/admin/trackinglogs') }}" class="small-box-footer">More info</a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection