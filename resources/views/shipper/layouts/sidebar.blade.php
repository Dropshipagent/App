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
            <li class="{{ Request::is('shipper/home') ? 'active' : '' }}">
                <a href="{{ url('/shipper/home') }}"><i class="fa fa-book"></i> <span>Mapped Stores</span></a>
            </li>
<!--            <li class="{{ Request::is('shipper/searchorder') ? 'active' : '' }}">
                <a href="{{ url('/shipper/searchorder') }}"><i class="fa fa-edit"></i> <span>Create Invoice</span></a>
            </li>-->
            <li class="{{ Request::is('shipper/shippernotifications') ? 'active' : '' }}">
                <a href="{{ url('/shipper/shippernotifications') }}"><i class="fa fa-bell"></i> <span>Notifications</span></a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>