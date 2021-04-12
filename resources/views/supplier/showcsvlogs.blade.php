@extends('supplier.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
@include('supplier.layouts.header-tabs')

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tr>
                    <th>Export #</th>
                    <th>Date Exported</th>
                    <th>Store Domain</th>
                    <th>Log file</th>
                </tr>
                <?php $i=1; ?>
                @foreach($cronorder_logs as $cronorder_log)
                <?php /*
                  echo "<pre>";
                  echo  count($user->storemap); die;
                 */ ?>
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ date("d/M/Y", strtotime($cronorder_log->created_at)) }}</td>
                    <td>{{ $cronorder_log->store_domain }}</td>
                    <td><a target="_blank" href="{{ URL::to('/').Storage::url('ordercsv/'.$cronorder_log->csv_file_name) }}">{{ $cronorder_log->csv_file_name }}</a></td>
                </tr>
                <?php $i++; ?>
                @endforeach
            </table>
        </div>
        <!-- /.box-body -->
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection