<!DOCTYPE html>
<html lang="en">
    <head>
        @include('shipper.layouts.head')
    </head>
    <body class="hold-transition skin-red sidebar-mini">
        <div class="wrapper">
            @include('shipper.layouts.header')
            @include('shipper.layouts.sidebar')
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @include('flash-message')

                @section('main-content')
                @show
            </div>
            
            @include('shipper.layouts.footer')
        </div>
        @include('shipper.layouts.foot')
         @yield('script')
    </body>
</html>