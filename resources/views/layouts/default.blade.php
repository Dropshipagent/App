<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.head')
    </head>
    <body class="skin-blue layout-top-nav" data-gr-c-s-loaded="true" style="height: auto; min-height: 100%;">
        <div class="wrapper">
            @include('layouts.default_header')
            <?php //@include('layouts.sidebar') content-wrapper ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @include('flash-message')

                @section('main-content')
                @show
            </div>

            <?php //@include('layouts.footer') ?>
        </div>
        @include('layouts.foot') 
    </body>
</html>
