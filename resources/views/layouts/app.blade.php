<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.head')
    </head>
    <body class="skin-purple hold-transition sidebar-mini">
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
            @include('layouts.header')
            @include('layouts.sidebar')
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @include('flash-message')

                @section('main-content')
                @show
            </div>

            <?php //@include('layouts.footer') ?>
        </div>
        @include('layouts.foot')
        @yield('script')
        <script type="text/javascript">
            $(document).ready(function () {
                notDelaySuccess();
                var delay = 10000;
                setInterval(function () {
                    notDelaySuccess();
                }, delay);
            });
            function notDelaySuccess() {
                var userID = '{{ auth()->user()->id }}';
                $.ajax({
                    type: 'POST',
                    url: '{{ url("user_not_count") }}',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {"user_id": userID},
                    success: function (data) {
                        if (data.data.success) {
                            $('.notCountShow').html(data.data.not_count);
                            $('.msgCountShow').html(data.data.msg_count);
                        }
                    }
                });
            }
        </script>
        @yield('style')
    </body>
</html>