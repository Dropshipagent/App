@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Notifications
        <small>list of all notifications</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('/admin/notifications') }}">Notifications</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box-header with-border">
        <h3 class="box-title">&nbsp;</h3>
        <div class="box-tools pull-right">
            <a href="{{ url('/admin/notifications/create') }}" class="btn btn-block btn-danger btn-sm">Add Notification</a>
        </div>
    </div>
    <div class="box">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tr>
                    <th>Notification For</th>
                    <th>Notification</th>
                    <th>Created</th>
                </tr>
                @foreach($notifications as $notification)
                <?php
                $notifor[1] = "All";
                $notifor[2] = "Store Owners";
                $notifor[3] = "Shipper";
                ?>
                <tr>
                    <td>{{ $notifor[$notification->user_role] }}</td>
                    <td>{{ $notification->notifications }}</td>
                    <td>{{ $notification->created_at }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="pull-right">
                {{ $notifications->links() }}
            </div>
        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection