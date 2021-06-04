<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <style>
            .chatwindowheading{
                background-color: #D7B441 !important; 
                padding: 8px 9px;  
                font-size:16px !important;
                color: #fff;
            }
            
            #chatWindow{
                padding:15px;
            }
            
            .chat {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .chat li {
                margin-bottom: 10px;
                padding-bottom: 10px;
                /*border-bottom: 1px dotted #B3A9A9;
                padding-right: 10px;*/
            }

            .chat li .leftbox p {
                margin: 0;
                color: #fff;
                background: #00008B;
                border-radius: 10px 10px 10px 0px;
                padding:5px 10px;
                float: left;
                max-width: 85%;
            }
            .chat li .rightbox{
                text-align:right;
            }
            .chat li .rightbox p {
                margin: 0;
                color: #101010;
                background: #ddd;
                border-radius: 10px 10px 0px 10px;
                padding:5px 10px;
                float: right;
                max-width: 85%;
            }
            
            .chat li .primary-font{
                text-transform: capitalize;
            }

            .panel-body {
                overflow-y: scroll;
                height: 470px;
            }
            ::-webkit-scrollbar-track {
                -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
                background-color: #F5F5F5;
            }

            ::-webkit-scrollbar {
                width: 12px;
                background-color: #F5F5F5;
            }

            ::-webkit-scrollbar-thumb {
                -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
                background-color: #555;
            }
            #btn-chat
                {
                    line-height:27px !important;
                    background-color: #D7B441 !important;
                    border-color: #D7B441 !important;
                    font-weight: bold;
                    border-radius:0px;
                    width: 70px;
                }
            
        </style>
        <?php $baseURL = URL::to('/'); ?>
        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
                    'csrfToken' => csrf_token(),
                    'receiverID' => "$receiver_id",
                    'baseURL' => "$baseURL",
                    'pusherKey' => config('broadcasting.connections.pusher.key'),
                    'pusherCluster' => config('broadcasting.connections.pusher.options.cluster')
            ]) !!}
            ;
        </script>
    </head>
    <body>
        <div id="app">
            <!--<nav class="navbar navbar-default navbar-static-top">
                <div class="container">
                   

                    <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <ul class="nav navbar-nav">
                            &nbsp;
                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                            @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                            @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav> -->

            @yield('content')
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>