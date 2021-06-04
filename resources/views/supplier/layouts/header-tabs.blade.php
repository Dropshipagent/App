<style>
    /* Style the tab */
    .header_tabs  {
        margin:20px 0;
    }

    /* Style the buttons inside the tab */
    .header_tabs a {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 14px;
        color:#FFF;
        border-radius: 30px;

    }

    /* Change background color of buttons on hover */
    .header_tabs a:hover {
        background-color: #FFD301;
        color: #000;
    }

    /* Create an active/current tablink class */
    .header_tabs a.active {
        background-color: #FFD301;
        color:#000;
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
<div class="row header_tabs">
    <?php
    $mapped_stores = helGetMappedStores(auth()->user()->id);
    //get session value of selected store
    $storeIDVal = Session::get('selected_store_id');
    ?>
    <div class="col-md-2 hidden">
        <select name="stores" class="form-control mappedStoreChange">
            <option value="0">Select Store</option>
            @foreach($mapped_stores as $mapped_store)
            <option value="{{ $mapped_store->store_id }}" {{ ($storeIDVal==$mapped_store->store_id)?"selected":"" }}>{{ $mapped_store->store_domain }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12 {{ ($storeIDVal>0)?"":"hidden" }}">
        <?php
        if ($storeIDVal > 0) {
            $createInvoices = url('supplier/bluckinvoice', $storeIDVal);
            $viewOrders = route('orders.show', $storeIDVal);
            $viewInvoicesLogs = url('supplier/showinvoiceslog', $storeIDVal);
            $viewTrackingLogs = url('supplier/trackinglogs', $storeIDVal);
            $viewCsvLogs = url('supplier/showcsvlogs');
            $quickChat = $storeIDVal;
        } else {
            $createInvoices = '#';
            $viewOrders = '#';
            $viewInvoicesLogs = '#';
            $viewTrackingLogs = '#';
            $viewCsvLogs = '#';
            $quickChat = $storeIDVal;
        }
        ?>
        <div>
            <a href="{{ $createInvoices }}" class="tablinks {{ Request::is('supplier/bluckinvoice*') ? 'active' : '' }}">Create Invoices</a>
            <a href="{{ $viewOrders }}" class="tablinks {{ Request::is('supplier/orders*') ? 'active' : '' }}">View Orders</a>
            <a href="{{ $viewInvoicesLogs }}" class="tablinks {{ Request::is('supplier/showinvoiceslog*') ? 'active' : '' }}">View Invoices Logs</a>
            <a href="{{ $viewTrackingLogs }}" class="tablinks {{ Request::is('supplier/trackinglogs*') ? 'active' : '' }}">View Tracking Logs</a>
            <a href="{{ url('/supplier/uploadtracking') }}" class="tablinks {{ Request::is('supplier/uploadtracking') ? 'active' : '' }}">Upload Tracking</a>
            <a href="{{ $viewCsvLogs }}" class="tablinks {{ Request::is('supplier/showcsvlogs*') ? 'active' : '' }}">Export History</a>
            <?php
            if ($quickChat > 0) {
                ?>
                <a href="javascript::void(0)" target="_blank" onClick="window.open('<?php echo url("chats", $storeIDVal); ?>', 'pagename', 'resizable,height=600,width=500'); return false;" class="btn btn-info margin2px">Quick Chat <span data-toggle="tooltip" title="" class="badge bg-red msgCountShow">0</span></a>
                <?php
            }
            ?>
        </div>  
    </div>
</div>