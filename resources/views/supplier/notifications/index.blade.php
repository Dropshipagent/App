@extends('supplier.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
@include('supplier.layouts.header-tabs')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#sent_noti" data-toggle="tab">Sent</a></li>
                    <li><a href="#received_noti" data-toggle="tab">Received</a></li>
                </ul>
                <div class="pull-right" style="position: absolute; right:20px; top:6px;">
                    <a href="{{ url('/supplier/suppliernotifications/create') }}" class="btn btn-block btn-danger btn-sm">Send Notification</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="sent_noti">
                        <table id="sent_notiData" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Notification For</th>
                                    <th>Notification</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                <tr>
                                    <td>{{ $notification->userdetail->username }}</td>
                                    <td>{{ $notification->notifications }}</td>
                                    <td>{{ $notification->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="received_noti">
                        <table id="received_notiData" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Notification From</th>
                                    <th>Notification</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recNotifications as $notification)
                                <tr>
                                    <td>
                                        <?php
                                        if (isset($notification->senduserdetail->username) && $notification->notification_by != 1) {
                                            echo "From " . $notification->senduserdetail->username;
                                        } else {
                                            echo "From Admin";
                                        }
                                        ?>
                                    </td>
                                    <td>{{ $notification->notifications }}</td>
                                    <td>{{ $notification->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#sent_notiData').DataTable({
            "order": [[2, "desc"]],
        });
        $('#received_notiData').DataTable({
            "order": [[2, "desc"]],
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var userID = '{{ auth()->user()->id }}';
        var notID = '{{ $notMaxID }}';
        $.ajax({
            type: 'POST',
            url: '{{ url("supplier/user_not_status") }}',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: {"user_id": userID, "not_id": notID},
            success: function (data) {
                if (data.data.success) {
                    //console.log("Notification read!");
                }
            }
        });
    });
</script>
@endsection