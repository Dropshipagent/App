<!DOCTYPE html>
<html lang="en">
    <head>
        @include('admin.layouts.head')
    </head>
    <body class="hold-transition skin-purple sidebar-mini">
        <!-- Common Modal -->
        <div id="alertMessageModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="wrapper">
            @include('admin.layouts.header')
            @include('admin.layouts.sidebar')
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @include('flash-message')

                @section('main-content')
                @show
            </div>

            @include('admin.layouts.footer')
        </div>
        @include('admin.layouts.foot')
        @yield('script')
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
                        url: '{{ url("admin/notifications_unread") }}',
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
                                            url = "{{ url('admin/notifications')}}";
                                        }
                                        $("#notification_list_header").append('<li><a href="' + url + '">' + item.notifications + '</a></li>');
                                    });
                                }
                            }
                        }
                    });
                });
                //call function to open corresponding tab
                explodeAndTrigerClick();
            });
            function notDelaySuccess() {
                var userID = '{{ auth()->user()->id }}';
                $.ajax({
                    type: 'POST',
                    url: '{{ url("admin/user_not_count") }}',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {"user_id": userID},
                    success: function (data) {
                        if (data.data.success) {
                            $('.notCountShow').html(data.data.not_count);
                        }
                    }
                });
            }
            explodeAndTrigerClick = () => {
                let currentURL = window.location.href;
                let urlData = currentURL.split("#");
                if (urlData[1]) {
                    let tabIDdata = urlData[1] + "_tab";
                    document.getElementById(tabIDdata).click();
                }
            }
        </script>
        @yield('style')
    </body>
</html>