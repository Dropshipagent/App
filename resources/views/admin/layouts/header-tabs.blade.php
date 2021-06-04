<style>
    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 0;
        background-color: transparent;
    }
    /* Style the buttons inside the tab */
    .tab a {
        border: none;
        outline: none;
        cursor: pointer;
        padding: 8px 16px;
        transition: 0.3s;
        font-size: 14px;
        color:#FFF;
        display: inline-block;
    }
    .tab_links_btn {
        margin: 15px 0;
        padding: 0 15px;
    }

    /* Change background color of buttons on hover */
    .tab a:hover {
        background-color: #ddd;
    }
    .tab a.active, .tab a:hover {
        background-color: #d7b441;
        color: #ffffff;
        font-weight: normal;
        border-radius: 100px;
    }

    /* Create an active/current tablink class */


    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
</style>
<div class="row tab">
    <?php
    //get session value of selected store
    $storeNameVal = Session::get('selected_store_id');
    //dd($storeIDVal);
    ?>
    <div class="col-md-12 {{ ($storeNameVal != "")?"":"hidden" }}">
        <?php
        if ($storeNameVal != "") {
            $viewProducts = url('admin/products/index', $storeNameVal);
            $viewOrders = url('/admin/orders');
            $viewInvoicesLogs = url('/admin/showinvoiceslog');
            $viewTrackingLogs = url('/admin/trackinglogs');
            $viewCsvLogs = url('/admin/users/showcsvlogs');
        } else {
            $viewProducts = '#';
            $viewOrders = '#';
            $viewInvoicesLogs = '#';
            $viewTrackingLogs = '#';
            $viewCsvLogs = '#';
        }
        ?>
        <div class="tab_links_btn">
            <a href="{{ $viewProducts }}" class="tablinks {{ Request::is('admin/products/index*') ? 'active' : '' }}">View Products</a>
            <a href="{{ $viewOrders }}" class="tablinks {{ Request::is('admin/orders') ? 'active' : '' }}">View Orders</a>
            <a href="{{ $viewInvoicesLogs }}" class="tablinks {{ Request::is('admin/showinvoiceslog') ? 'active' : '' }}">View Invoices</a>
            <a href="{{ $viewTrackingLogs }}" class="tablinks {{ Request::is('admin/trackinglogs') ? 'active' : '' }}">View Tracking Logs</a>
            <a href="{{ $viewCsvLogs }}" class="tablinks {{ Request::is('admin/users/showcsvlogs') ? 'active' : '' }}">View CSV Logs</a>
        </div>  
    </div>
</div>