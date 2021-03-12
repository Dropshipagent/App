@extends('layouts.app')
@section('title', 'Profile Status')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">

</section>
<!-- Main content -->
<section class="content profilependingbox">

    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        <p>Welcome {{ auth()->user()->name }}</p>
        <p>Your     application is successfully submitted to Dropship Agent. One of our team members will reach out to you shortly with steps to verify your profile.<br />Please reach out to us at support@dropshipagent.co for any further queries/enquiries</p>
    </div>

</section>
<!-- /.content -->
<section class="blackBoxBg">
    <div class="row">
        <div class="col-sm-3">
            <img src="{{URL::to('/')}}/img/afasd.png" alt="Connect Dropship Agent to your store" width="100" height="100">
            <h2>Connect Dropship Agent to your store</h2>
            <p>With a simple click of a mouse</p>
        </div> 

        <div class="col-sm-3">
            <img src="{{URL::to('/')}}/img/123123.png" alt="Auto Fulfill" width="100" height="100">
            <h2>Auto Fulfill</h2>
            <p>We will automatically export orders. You pick a schedule, we do the rest.</p>
        </div> 

        <div class="col-sm-3">
            <img src="{{URL::to('/')}}/img/123124.png" alt="Faster Shipping" width="100" height="100">
            <h2>Faster Shipping</h2>
            <p>Enjoy peace of mind with fully trackable 7-10 day worldwide shipping.</p>
        </div> 

        <div class="col-sm-3">
            <img src="{{URL::to('/')}}/img/12223.png" alt="Enjoy the extra profit" width="100" height="100">
            <h2>Enjoy the extra profit</h2>
            <p>On average, our clients make 25% extra immediately when sourcing with Dropship Agent</p>
        </div> 

        </div>
</section>
<section class="greenBoxBg">
    <div class="row">
        <div class="col-sm-4">
            <h2>1m+</h2>
            <p>Orders delivered directly to customers </p>
        </div> 
        <div class="col-sm-4">
            <h2>100%</h2>
            <p>Guaranteed quality and delivery of orders </p>
        </div> 
        <div class="col-sm-4">
            <h2>120+</h2>
            <p>Very happy clients  </p>
        </div>    
    </div>
</section>
@endsection