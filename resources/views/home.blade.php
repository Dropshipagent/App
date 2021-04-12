@extends('layouts.app')
@section('title', 'Dashboard')
@section('main-content')

<!-- Main content -->
<section class="content">


    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12 welcome-div">
                    <h1>{{helGetClockMessage()}}, <span class="text-capitalize">{{auth()->user()->name}}!</span></h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Default box -->
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-first">
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
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-second">
                        <div class="inner">
                            <h3>{{ $uploadedTracking }}</h3>

                            <p>Total Uploaded Tracking</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-social-dropbox"></i>
                        </div>
                        <a href="{{ url('showtrackinglog') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-third">
                        <div class="inner">
                            <h3>{{ $orders }}</h3>

                            <p>Total Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ url('orders') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-fourth">
                        <div class="inner">
                            <h3>{{ $flagProducts }}</h3>

                            <p>Total Sourced Products</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-social-dropbox"></i>
                        </div>
                        <a href="{{ url('storeproducts/index', auth()->user()->username) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @if($adminAcceptedProducts > 0)
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-first">
                        <div class="inner">
                            <h3>{{ $adminAcceptedProducts }}</h3>

                            <p>Admin Accepted Products</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ url('storeproducts/index', auth()->user()->username) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endif
                <!-- ./col -->
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <div class="box latest_content">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-uppercase text-center mb-3">LATEST <span class="color_ye">DROPSHIP</span>AGENT NEWS</h1>

                    @foreach($news_data as $news)
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="card">
                            <div class="view overlay">
                                <?php $imageurl = ($news->image) ?  url('storage/news/images/'.$news->image) :  url('img/no-mage.jpg'); ?>
                                <img class="card-img-top img-fluid img-thumbnail" src="{{ $imageurl }}" alt="Card image cap">
                                <a href="#!">
                                    <div class="mask rgba-white-slight waves-effect waves-light"></div>
                                </a>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title">{{ $news->title }}</h4>
                                <?php if($news->title) { ?>
                                <div class="card-text"><?php echo $news->description; ?></div>
                                <?php } ?>
                                
                                <a href="{{ $news->link }}" class="btn btn-primary waves-effect waves-light" target="_blank">Read More</a>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- 
        <div class="box more_content">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12 div_custom_center">
                        <h2><i>More Content here</i></h2>
                    </div>
                </div>
            </div>
        </div> -->

</section>
<!-- /.content -->
@endsection