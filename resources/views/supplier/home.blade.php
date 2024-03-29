@extends('supplier.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
@include('supplier.layouts.header-tabs')
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
                <div class="col-lg-4 col-xs-8">
                    <!-- small box -->
                    <div class="small-box bg-aqua qwe">
                        <div class="inner">
                            <h3>{{ $orders }}</h3>

                            <p>Total Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ url('/supplier/stores') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-xs-8">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{ $supplierstores }}</h3>

                            <p>Total Assigned Stores</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ url('/supplier/stores') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-xs-8">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ $invoicesLogs }}</h3>

                            <p>Total Invoices</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ url('/supplier/stores') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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