<!DOCTYPE html>
<html lang="en">
    <head>
        @include('admin.layouts.head')
    </head>
    <body class="hold-transition skin-purple sidebar-mini">
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
        </script>
        @yield('style')
    </body>
</html>