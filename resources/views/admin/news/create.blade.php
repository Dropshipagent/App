@extends('admin.layouts.app')
@section('title', 'Admin Settings')
@section('main-content')
<script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-body table-responsive no-padding">
            <div class="container-fluid">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Setting Form</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" enctype="multipart/form-data" action="{{url('admin/news')}}">
                        {{ csrf_field() }}
                        <?php
                        if (isset($categorie)) {
                            ?>
                            {{ method_field('PATCH') }}
                            <?php
                        }
                        ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ (!empty($setting) && $setting->title!='')? $setting->title : old('title') }}">
                                        @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong class="text-danger">{{ $errors->first('title') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                       <textarea name="description" class="form-control" rows="10">{{ (!empty($setting) && $setting->description!='')? $setting->description : old('description') }}</textarea>
                                        @if ($errors->has('description'))
                                        <span class="help-block">
                                            <strong class="text-danger">{{ $errors->first('description') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Link</label>
                                        <input type="url" name="link" class="form-control" value="{{ (!empty($setting) && $setting->link!='')? $setting->link : old('link') }}">
                                        @if ($errors->has('link'))
                                        <span class="help-block">
                                            <strong class="text-danger">{{ $errors->first('link') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Blog Image</label>
                                        <input type="file" class="form-control" name="image">
                                        @if ($errors->has('image'))
                                        <span class="help-block">
                                            <strong class="text-danger">{{ $errors->first('image') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <br>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
<script type="text/javascript">
CKEDITOR.replace('description');
</script>
@endsection