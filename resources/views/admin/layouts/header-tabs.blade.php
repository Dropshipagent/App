<style>
    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #fff;
    }

    /* Style the buttons inside the tab */
    .tab a {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 14px;
        color:#101010

    }

    /* Change background color of buttons on hover */
    .tab a:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab a.active {
        background-color: #fff;
        color:#d7b441;
        font-weight: bold;
    }

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
        } else {
            $viewProducts = '#';
            $viewOrders = '#';
            $viewInvoicesLogs = '#';
        }
        ?>
        <div>
            <a href="{{ $viewProducts }}" class="tablinks {{ Request::is('admin/products/index*') ? 'active' : '' }}">View Products</a>
            <a href="{{ $viewOrders }}" class="tablinks {{ Request::is('admin/orders') ? 'active' : '' }}">View Orders</a>
            <a href="{{ $viewInvoicesLogs }}" class="tablinks {{ Request::is('admin/showinvoiceslog') ? 'active' : '' }}">View Invoices</a>
        </div>  
    </div>
</div>