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
                        <h3 class="card-title">Setting</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" action="{{ url($action) }}">
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
                                        <label>Intro Video Url</label>
                                        <input type="text" class="form-control" value="{{ (!empty($setting) && $setting->intro_video_url!='')? $setting->intro_video_url : old('intro_video_url') }}" name="intro_video_url" />
                                        @if ($errors->has('intro_video_url'))
                                        <span class="help-block">
                                            <strong class="text-danger">{{ $errors->first('intro_video_url') }}</strong>
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
CKEDITOR.replace('store_news');
</script>
@endsection