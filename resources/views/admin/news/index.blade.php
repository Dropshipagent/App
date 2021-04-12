@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        News
        <small>list of all news</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('/admin/news') }}">News</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <div class="pull-right" style="position: absolute; right:20px; top:6px;">
                    <a href="{{ url('/admin/news/create') }}" class="btn btn-block btn-danger btn-sm">Add News</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="sent_noti">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($news_data as $news)                                
                                <tr>
                                   <td>
                                   <?php if($news->image) { ?>
                                      <img src="{{ url('storage/news/images/'.$news->image) }}" width="100">
                                 <?php  } ?>
                                   </td>
                                   <td>{{ $news->title }}</td>
                                   <td>{{ $news->created_at }}</td>
                                   <td>
                                        <a href="{{ URL::to('admin/news/' . $news->id . '/edit') }}" class="btn btn-primary margin2px" title="Edit Product"><i class="fa fa-edit"></i></a>
                                        <form action="{{ URL::to('admin/news/' . $news->id) }}" method="POST" style="display: inline" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="_method" value="DELETE">
                                            {{ csrf_field() }}
                                            <button class="btn btn-danger margin2px" title="Delete"><i class="fa fa-trash"></i></button>
                                        </form>
                                   </td>
        
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

@endsection