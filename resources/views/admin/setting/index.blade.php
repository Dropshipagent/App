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
                                        <label>Store News</label>
                                        <textarea name="store_news" class="form-control" rows="10">{{ (!empty($setting) && $setting->store_news!='')? $setting->store_news : old('store_news') }}</textarea>
                                        @if ($errors->has('store_news'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('store_news') }}</strong>
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