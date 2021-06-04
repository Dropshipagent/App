<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="{{ Request::is('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}"><!-- <i class="fa fa-dashboard"></i> -->
                    <img class="side-bar-icon" src="{{asset('img/sidebar-icon-1.png')}}" alt="side-bar-icon">
                     <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ Request::is('showinvoiceslog') ? 'active' : '' }}">
                <a href="{{ url('showinvoiceslog') }}"><!-- <i class="fa fa-info"></i> --> 
                    <img class="side-bar-icon" src="{{ asset('img/user-icon.png') }}" alt="side-bar-icon">
                    <span>Invoices</span>
                </a>
            </li>
            <li class="{{ Request::is('showtrackinglog') ? 'active' : '' }}">
                <a href="{{ url('showtrackinglog') }}">
                    <!-- <i class="fa fa-plane"></i>  -->
                     <img class="side-bar-icon" src="{{ asset('img/sidebar-icon-3.png') }}" alt="side-bar-icon">
                    <span>Tracking Log</span>
                </a>
            </li>
            <li class="{{ Request::is('storeproducts/*') ? 'active' : '' }}">
                <a href="{{ url('storeproducts/index', auth()->user()->username) }}">
                    <!-- <i class="fa fa-product-hunt"></i>  -->
                    <img class="side-bar-icon" src="{{ asset('img/notification.png') }}" alt="side-bar-icon">
                    <span>Products</span>
                </a>
            </li>
            <li class="{{ Request::is('orders') ? 'active' : '' }}">
                <a href="{{ url('orders') }}">
                    <!-- <i class="fa fa-shopping-cart"></i>  -->
                    <img class="side-bar-icon" src="{{ asset('img/megaphone.png') }}" alt="side-bar-icon">
                    <span>Orders</span>
                </a>
            </li>
            <li class="{{ Request::is('my-account') ? 'active' : '' }}">
                <a href="{{ url('my-account') }}">
                    <!-- <i class="fa fa-user"></i>  -->
                    <img class="side-bar-icon" src="{{ asset('img/settings.png') }}" alt="side-bar-icon">
                    <span>My Account</span>
                </a>
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
            @if(helGetSupplierID(Auth::user()->id) > 0)
            <li>
                <a href="javascript::void(0)" target="_blank" onClick="window.open('{{ url('chats', helGetSupplierID(Auth::user()->id)) }}', 'pagename', 'resizable,height=560,width=430'); return false;" title="Start Chat with Supplier">
                    <i class="fa fa-comments-o"></i> <span>Quick Chat</span> 
                    <span data-toggle="tooltip" title="" class="badge bg-red msgCountShow">0</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>