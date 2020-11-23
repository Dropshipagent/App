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
    </body>
</html>