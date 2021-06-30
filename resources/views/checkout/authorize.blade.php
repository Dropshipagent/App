@extends('layouts.app')
@section('title', 'Make Payment')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="hidden">Payment Details</h1>
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-body">

                    @if (Session::has('success'))
                    <div class="alert alert-success text-center">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <p>{{ Session::get('success') }}</p>
                    </div>
                    @endif

                    <div class="content activating_account_box">
                        <form class="form-horizontal" action="{{ url('/checkout/'.auth()->user()->username) }}" method="post">
                            {{ csrf_field() }}
                            <h3>Hi {{ auth()->user()->name }}</h3>
                            <p>We are happy to inform you that the Dropship Agent team has approved your product source request and we’d be happy to serve your product needs. Please check the suggested prices for your requested products below:</p>
                            <p><a class="btn btn-success" href="{{ url('storeproducts/index', auth()->user()->username) }}">View Products</a></p>
                            <p>Please subscribe to a $59 monthly fee to start sourcing with Dropship Agent now.</p>
                            <p><button type="submit" class="btn btn-primary">Subscribe</button></p>
                            <p>Happy to have you onboard</p>
                            <p>Team Dropship Agent</p>
                        </form>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
