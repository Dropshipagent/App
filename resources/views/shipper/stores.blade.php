@extends('shipper.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tr>
                    <th>ID</th>
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
                    <td>{{ $mapped_store->id }}</td>
                    <td>{{ $mapped_store->store_domain }}</td>
                    <td>{{ $mapped_store->created_at }}</td>
                    <td>
                        <a href="{{ url('shipper/set_store_session', $mapped_store->store_id) }}" class="btn btn-warning mappedStoreChange" title="Select Store">Proceed</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
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