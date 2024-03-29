@extends('supplier.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="">
        <div class="table-responsive supplier_page">
            <table class="table table-hover">
                <tr>
                    <th>Name</th>
                    <th>Store</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
                @foreach($mapped_stores as $mapped_store)
                <?php
                //get session value of selected store
                $storeIDVal = Session::get('selected_store_id');
                ?>
                <tr>
                    <td>{{ $mapped_store->storeDetails->name }}</td>
                    <td>{{ $mapped_store->store_domain }}</td>
                    <td>{{ $mapped_store->created_at }}</td>
                    <td>
                        <a href="{{ url('supplier/set_store_session', $mapped_store->store_id) }}" class="btn btn-warning mappedStoreChange" title="Select Store">Proceed</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.box-body -->
        <div class="footer-tablee">
            <div class="pull-right">
                {{ $mapped_stores->links() }}
            </div>
        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection