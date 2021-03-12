@extends('admin.layouts.app')
@section('title', 'Create User')
@section('main-content')
<!-- include tags css -->
<link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Create Supplier
        <small>add new supplier details</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('/admin/users') }}">Users</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-warning">
        <div class="box-header with-border">
            <div class="pull-left">
                <h3 class="box-title">Add New Record</h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
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
            {!! Form::open(array('route' => 'users.store','id' => 'userForm','files'=>true,'method'=>'POST')) !!}
            {!! Form::hidden('role', 3) !!}

            <!-- text input -->
            <div class="form-group">
                <strong>Full Name:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                <strong>Username:</strong>
                {!! Form::text('username', null, array('placeholder' => 'Username','class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                <strong>Email:</strong>
                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                <strong>Password:</strong>
                {!! Form::password('password', array('placeholder'=>'Password', 'class'=>'form-control')) !!}
            </div>
            <div class="form-group">
                <strong>Confirm Password:</strong>
                {!! Form::password('password_confirmation', array('placeholder'=>'Password', 'class'=>'form-control')) !!}    
            </div>
            <div class="form-group">
                <strong>Tags:</strong><br>
                <strong>Use "Enter" key to add a variation tag for this supplier</strong><br>
                {!! Form::text('tags', null, array('placeholder' => 'Tags','class' => 'form-control','data-role' => 'tagsinput')) !!}
            </div>
            <div class="form-group">
                <button type="button" id="submitBtn" class="btn btn-primary">Submit</button> 
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.box-body -->
    </div>
</section>
<!-- include tags jquery -->
<script src="{{ asset('admin/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $("#submitBtn").click(function () {
        $("#userForm").submit(); // Submit the form
    });
});
</script>
<!-- /.content -->
@endsection