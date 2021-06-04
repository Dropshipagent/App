@extends('admin.layouts.app')
@section('title', 'Create User')
@section('main-content')
<!-- include tags css -->
<section class="content-header">
    <h1>
        Supplier Detail
        <small>with order list</small>
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
                <h3 class="box-title">Details</h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
        <!-- /.box-header -->
        @include('common.ordertable')
        <!-- /.box-body -->
    </div>
</section>
<!-- /.content -->
@endsection