<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.head')
    </head>
    <body class="skin-blue hold-transition sidebar-mini">
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
        @if(auth()->user()->status > 1)
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
        @endif
        @yield('style')
    </body>
</html>