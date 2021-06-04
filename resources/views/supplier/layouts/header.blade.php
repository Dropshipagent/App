<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <img src="{{ asset('/img/logo_new.png') }}" class="logo-img">
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <div class="content_width_box">
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning notCountShow">0</span>
                        </a>
                        <ul class="dropdown-menu">

                            <li class="header">You have <span class="notCountShow">0</span> notifications</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu" id="notification_list_header">

                                </ul>
                            </li>
                            <li class="footer"><a href="<?php echo url('supplier/suppliernotifications'); ?>">View all</a></li>
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