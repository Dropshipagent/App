@extends('supplier.layouts.app')
@section('title', 'Create Notification')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Send Notification
        <small>add new notification</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/supplier/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('suppliernotifications.index') }}">Notifications</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-warning">
        <div class="box-header with-border">
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('suppliernotifications.index') }}"> Back</a>
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
            {!! Form::open(array('route' => 'suppliernotifications.store','id' => '','files'=>true,'method'=>'POST')) !!}
            {!! Form::hidden('notification_by', auth()->user()->id) !!}

            <!-- text input -->
            <div class="form-group">
                <strong>Send To:</strong><br>
                @foreach ($mappedstores as $key => $val)
                <label class="checkbox-inline" for="inlineCheckbox{{ $key }}">
                    <input type="checkbox" class="notification_send_to" id="inlineCheckbox{{ $key }}" name="stores[]" value="{{ $key }}"> {{ $val }}
                </label>
                @endforeach
            </div>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#submitBtn').on('click', function () {
            var checked = $(".notification_send_to:checked").length;
            if (checked < 1) {
                alert("Please select at least one recipient");
                return false;
            }
            var notificationStr = $(".notifications_text").val();
            var trimStr = $.trim(notificationStr);
            if (trimStr == "") {
                alert("Notification should not be blank.");
                return false;
            }

            return true;
        });
    });
</script>
@endsection