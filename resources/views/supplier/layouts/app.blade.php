<!DOCTYPE html>
<html lang="en">
    <head>
        @include('supplier.layouts.head')
    </head>
    <body class="hold-transition skin-red sidebar-mini">
        <div class="wrapper">
            @include('supplier.layouts.header')
            @include('supplier.layouts.sidebar')
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @include('flash-message')

                @section('main-content')
                @show
            </div>

            @include('supplier.layouts.footer')
        </div>
        @include('supplier.layouts.foot')
        @yield('script')
        <?php
        $storeIDVal = (Session::get('selected_store_id')) ? Session::get('selected_store_id') : 0;
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                notDelaySuccess();
                var delay = 10000;
                setInterval(function () {
                    notDelaySuccess();
                }, delay);
                $(".notifications-menu").click(function () {
                    var userID = '{{ auth()->user()->id }}';
                    $.ajax({
                        type: 'POST',
                        url: '{{ url("supplier/notifications_unread") }}',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        data: {"user_id": userID},
                        success: function (data) {
                            if (data.data.success) {
                                if (data.data.notifications && data.data.notifications.length > 0) {
                                    $("#notification_list_header").html('');
                                    $.each(data.data.notifications, function (i, item) {
                                        if (item.notification_url != "") {
                                            url = "{!!url('" + item.notification_url + "')!!}";
                                        } else {
                                            url = "{{ url('supplier/suppliernotifications')}}";
                                        }
                                        $("#notification_list_header").append('<li><a href="' + url + '">' + item.notifications + '</a></li>');
                                    });
                                }
                            }
                        }
                    });
                });
            });
            function notDelaySuccess() {
                var userID = '{{ auth()->user()->id }}';
                var storeID = '{{ $storeIDVal }}';
                $.ajax({
                    type: 'POST',
                    url: '{{ url("supplier/user_not_count") }}',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {"user_id": userID, "store_id": storeID},
                    success: function (data) {
                        if (data.data.success) {
                            $('.msgCountShow').html(data.data.msg_count);
                            $('.notCountShow').html(data.data.not_count);
                        }
                    }
                });
            }
        </script>
        @yield('style')
    </body>
</html>