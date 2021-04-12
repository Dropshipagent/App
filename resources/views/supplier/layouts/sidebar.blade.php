<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="{{ Request::is('supplier/home') ? 'active' : '' }}">
                <a href="{{ url('/supplier/home') }}"><i class="fa fa-book"></i> <span>Mapped Stores</span></a>
            </li>
            <!--            <li class="{{ Request::is('supplier/searchorder') ? 'active' : '' }}">
                            <a href="{{ url('/supplier/searchorder') }}"><i class="fa fa-edit"></i> <span>Create Invoice</span></a>
                        </li>-->
            <li class="{{ Request::is('supplier/suppliernotifications') ? 'active' : '' }}">
                <a href="{{ url('/supplier/suppliernotifications') }}"><i class="fa fa-bell"></i> <span>Notifications</span></a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>