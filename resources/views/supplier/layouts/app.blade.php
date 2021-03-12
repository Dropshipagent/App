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
    </body>
</html>