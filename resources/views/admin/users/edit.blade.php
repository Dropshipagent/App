@extends('admin.layouts.app')
@section('title', 'Edit Store')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Edit Store
        <small></small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-warning">
        <div class="box-header with-border">
            <div class="pull-left">
                <h3 class="box-title">Edit Record</h3>
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
            {!! Form::model($user, ['route' => ['users.update', $user->id],'files'=>true,'method'=>'PATCH']) !!}
            <div class="form-group">
                <strong>Store Name:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                <strong>Email:</strong>
                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                <strong>Status:</strong>
                <select class="form-control" name="status">
                    <option value="0" {{ ((isset($user->status) && $user->status== 0)? "selected":"") }}>Pending</option>
                    <option value="1" {{ ((isset($user->status) && $user->status== 1)? "selected":"") }}>Confirmed and uninitiated</option>
                    <option value="2" {{ ((isset($user->status) && $user->status== 2)? "selected":"") }}>Initiated</option>
                </select>
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