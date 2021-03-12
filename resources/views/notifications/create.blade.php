@extends('layouts.app')
@section('title', 'Create Notification')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Send Notification to Supplier
        <small>add new notification</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('storenotifications.index') }}">Notifications</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-warning">
        <div class="box-header with-border">
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('storenotifications.index') }}"> Back</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            {!! Form::open(array('route' => 'storenotifications.store','id' => '','files'=>true,'method'=>'POST')) !!}
            {!! Form::hidden('notification_by', auth()->user()->id) !!}
            {!! Form::hidden('user_id', helGetSupplierID(auth()->user()->id)) !!}
            {!! Form::hidden('user_role', 3) !!}

            <!-- text input -->
            <div class="form-group">
                <strong>Notification Text:</strong>
                {!! Form::textarea('notifications',null,['class'=>'form-control notifications_text', 'required' => 'required', 'rows' => 3, 'cols' => 40]) !!}
            </div>
            <div class="form-group">
                <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button> 
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.box-body -->
    </div>
</section>
<!-- /.content -->
@endsection