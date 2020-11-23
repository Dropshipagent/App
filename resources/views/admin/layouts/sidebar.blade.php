<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="{{ Request::is('admin/home') ? 'active' : '' }}">
                <a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
            <li class="{{ Request::is('admin/orders') ? 'active' : '' }}">
                <a href="{{ url('/admin/orders') }}"><i class="fa fa-book"></i> <span>Orders Listing</span></a>
            </li>
            <li class="{{ Request::is('admin/users') ? 'active' : '' }}">
                <a href="{{ url('/admin/users') }}"><i class="fa fa-user-o"></i> <span>Users Listing</span></a>
            </li>
            <li class="{{ Request::is('admin/showinvoiceslog') ? 'active' : '' }}">
                <a href="{{ url('/admin/showinvoiceslog') }}"><i class="fa fa-list"></i> <span>Invoices Logs</span></a>
            </li>
            <li class="{{ Request::is('admin/trackinglogs') ? 'active' : '' }}">
                <a href="{{ url('/admin/trackinglogs') }}"><i class="fa fa-bars"></i> <span>Tracking Logs</span></a>
            </li>
            <li class="{{ Request::is('admin/notifications') ? 'active' : '' }}">
                <a href="{{ url('/admin/notifications') }}"><i class="fa fa-bell"></i> <span>Notifications</span></a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>