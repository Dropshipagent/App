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
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#received_noti" data-toggle="tab">Received</a></li>
                    <li><a href="#sent_noti" data-toggle="tab">Sent</a></li>
                </ul>
                <div class="pull-right" style="position: absolute; right:50px; top:30px;">
                    <a href="{{ url('/admin/notifications/create') }}" class="btn btn-block btn-danger btn-sm">Send Notification</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active table-responsive" id="received_noti">
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
                                    @if(isset($notification->senduserdetail->username))         
                                    <td>{{ $notification->senduserdetail->username }}</td>
                                    @else
                                    <td>Unknown</td>        
                                    @endif
                                    <td><?php echo ($notification->notification_url != "") ? '<a href="' . url($notification->notification_url) . '">' . $notification->notifications . '</a>' : $notification->notifications ?></td>
                                    <td>{{ $notification->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="sent_noti">
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
                                <?php
                                $notifor[1] = "All";
                                $notifor[2] = "Store Owners";
                                $notifor[3] = "Supplier";
                                ?>
                                <tr>
                                    @if($notification->user_role == 0)
                                    @if(isset($notification->userdetail->username))         
                                    <td>{{ $notification->userdetail->username }}</td>
                                    @else
                                    <td>Unknown</td>        
                                    @endif
                                    @else
                                    <td>{{ $notifor[$notification->user_role] }}</td>
                                    @endif
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
            url: '{{ url("admin/user_not_status") }}',
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