@extends('layouts.app')
@section('title', 'Dashboard')
@section('main-content')

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ $storeInvoices }}</h3>

                            <p>Total Invoices</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ url('showinvoiceslog') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
<!--                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
                     small box 
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $products }}</h3>

                            <p>Total Products</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ url('storeproducts/index', auth()->user()->username) }}?type=all" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>-->
                <!-- ./col -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $flagProducts }}</h3>

                            <p>Total Sourced Products</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ url('storeproducts/index', auth()->user()->username) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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