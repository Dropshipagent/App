<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="{{ Request::is('admin/home') ? 'active' : '' }}">
                <a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
<!--            <li class="{{ Request::is('admin/orders') ? 'active' : '' }}">
                <a href="{{ url('/admin/orders') }}"><i class="fa fa-book"></i> <span>Orders Listing</span></a>
            </li>-->
            <li class="{{ Request::is('admin/users') ? 'active' : '' }}">
                <a href="{{ url('/admin/users') }}"><i class="fa fa-user-o"></i> <span>Users Listing</span></a>
            </li>
<!--            <li class="{{ Request::is('admin/showinvoiceslog') ? 'active' : '' }}">
                <a href="{{ url('/admin/showinvoiceslog') }}"><i class="fa fa-list"></i> <span>Invoices Logs</span></a>
            </li>-->
            <li class="{{ Request::is('admin/trackinglogs') ? 'active' : '' }}">
                <a href="{{ url('/admin/trackinglogs') }}"><i class="fa fa-bars"></i> <span>Tracking Logs</span></a>
            </li>
            <li class="{{ Request::is('admin/notifications') ? 'active' : '' }}">
                <a href="{{ url('/admin/notifications') }}"><i class="fa fa-bell"></i> <span>Notifications</span>
                    <span data-toggle="tooltip" title="" class="badge bg-red notCountShow">0</span>
                </a>
            </li>
            <li class="{{ Request::is('admin/news') ? 'active' : '' }}">
                <a href="{{ url('/admin/news') }}"><i class="fa fa-bell"></i> <span>News</span>
                </a>
            </li>
            <li class="{{ Request::is('admin/setting') ? 'active' : '' }}">
                <a href="{{ url('/admin/setting') }}"><i class="fa fa-bell"></i> <span>Settings</span></a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>