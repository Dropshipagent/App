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
            $viewCsvLogs = url('supplier/showcsvlogs', $storeIDVal);
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
                <a href="{{ url('/supplier/suppliernotifications') }}" class="tablinks {{ Request::is('supplier/suppliernotifications*') ? 'active' : '' }}">Notifications 
                    <span data-toggle="tooltip" title="" class="badge bg-red notCountShow">0</span>
                </a>
                <a href="javascript::void(0)" target="_blank" onClick="window.open('<?php echo url("chats", $storeIDVal); ?>', 'pagename', 'resizable,height=600,width=500'); return false;" class="btn btn-info margin2px">Quick Chat <span data-toggle="tooltip" title="" class="badge bg-red msgCountShow">0</span></a>
                <?php
            }
            ?>
        </div>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        notDelaySuccess();
        var delay = 10000;
        setInterval(function () {
            notDelaySuccess();
        }, delay);
    });
    function notDelaySuccess() {
        var userID = '{{ auth()->user()->id }}';
        var storeID = '{{ $storeIDVal }}';
        $.ajax({
            type: 'POST',
            url: '{{ url("supplier/user_not_count") }}',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: {"user_id": userID, "store_id": storeID},
            success: function (data) {
                if (data.data.success) {
                    $('.notCountShow').html(data.data.not_count);
                    $('.msgCountShow').html(data.data.msg_count);
                }
            }
        });
    }
</script>