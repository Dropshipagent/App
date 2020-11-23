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
            <li class="{{ Request::is('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
            <li class="{{ Request::is('showinvoiceslog') ? 'active' : '' }}">
                <a href="{{ url('showinvoiceslog') }}"><i class="fa fa-info"></i> <span>Invoices</span></a>
            </li>
            <li class="{{ Request::is('showtrackinglog') ? 'active' : '' }}">
                <a href="{{ url('showtrackinglog') }}"><i class="fa fa-plane"></i> <span>Tracking Log</span></a>
            </li>
            <li class="{{ Request::is('storeproducts/*') ? 'active' : '' }}">
                <a href="{{ url('storeproducts/index', auth()->user()->username) }}"><i class="fa fa-product-hunt"></i> <span>Products</span></a>
            </li>
            <li class="{{ Request::is('orders') ? 'active' : '' }}">
                <a href="{{ url('orders') }}"><i class="fa fa-shopping-cart"></i> <span>Orders</span></a>
            </li>
            <li class="{{ Request::is('my-account') ? 'active' : '' }}">
                <a href="{{ url('my-account') }}"><i class="fa fa-user"></i> <span>My Account</span></a>
            </li>
            <li class="{{ Request::is('faqs') ? 'active' : '' }}">
                <a href="{{ url('faqs') }}"><i class="fa fa-question-circle"></i> <span>FAQ’s</span></a>
            </li>
            <li class="{{ Request::is('shipping-info') ? 'active' : '' }}">
                <a href="{{ url('shipping-info') }}"><i class="fa fa-truck"></i> <span>Shipping Info</span></a>
            </li>
            <li class="{{ Request::is('notifications') ? 'active' : '' }}">
                <a href="{{ url('storenotifications') }}"><i class="fa fa-flag-checkered"></i> <span>Notifications</span> 
                    <span data-toggle="tooltip" title="" class="badge bg-red notCountShow">0</span>
                </a>
            </li>
            @if(helGetShipperID(Auth::user()->id) > 0)
            <li>
                <a href="javascript::void(0)" target="_blank" onClick="window.open('{{ url('chats', helGetShipperID(Auth::user()->id)) }}', 'pagename', 'resizable,height=560,width=430'); return false;" title="Start Chat with Shipper">
                    <i class="fa fa-comments-o"></i> <span>Quick Chat</span> 
                    <span data-toggle="tooltip" title="" class="badge bg-red msgCountShow">0</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>