<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <!-- {{ asset('admin/dist/img/logo.png') }} -->
        <img src="{{ asset('img/logo_new.png') }}" class="logo-img">
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <div class="content_width_box">
            <!-- <div class="search_bar">
                <form role="search" method="get" class="search-form">
                    <input type="text" class="search-field" placeholder="Search …" value="" name="s" title="Search for:" required="">
                    <button type="submit" class="search-field1"><i class="fa fa-search"></i></button>
                </form>
            </div>
            -->            <div class="navbar-custom-menu">                
                <ul class="nav navbar-nav">
                    <!-- <li class="dropdown chatting-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <img class="right-side-bar-icon" src="{{asset('img/chat.png')}}" alt="right-side-bar-icon">
                            <span class="label red_toge notCountShow">0</span>
                        </a>
                    </li> -->
                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <!-- <i class="fa fa-bell-o"></i> -->                        
                            <img class="right-side-bar-icon" src="{{asset('img/right-bar_notification.png')}}" alt="right-side-bar-icon">
                            <span class="label red_toge notCountShow">0</span>
                        </a>
                        <ul class="dropdown-menu">

                            <li class="header">You have <span class="notCountShow">0</span> notifications</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu" id="notification_list_header">
                                </ul>
                            </li>
                            <li class="footer"><a href="<?php echo url('storenotifications'); ?>">View all</a></li>
                        </ul>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
                            <span class="hidden-xs text-capitalize">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ url('my-account') }}" class="btn btn-default btn-flat">My Account</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>

                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span class="sr-only">Toggle navigation</span>
                </a>
            </div>

        </div>
    </nav>
</header>