@extends('shipper.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
@include('shipper.layouts.header-tabs')
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table" >
                    <section class="content-header">
                        <h1>Upload Tracking Info</h1>
                    </section>                    
                </div>
                <div class="panel-body">

                    {!! Form::open(['method'=>'POST','accept-charset'=>'UTF-8','files'=>true]) !!}

                    <div class='form-row row'>
                        <div class='col-xs-12 form-group required'>
                            <label class='control-label'>Select a File</label> 
                            {!! Form::file('file',['required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block" type="submit">Submit</button>
                        </div>
                    </div>

                    </form>
                </div>
            </div>        
        </div>
    </div>
</section>
<!-- /.content -->
@endsection