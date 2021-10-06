@extends('supplier.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<!-- Content Header (Page header) -->
@include('supplier.layouts.header-tabs')
<!-- Main content -->
<section class="content spreadsheetdata">
    <!-- Default box -->
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table">
                    <section class="content-header">
                        <h1>Select Headers</h1>
                    </section>
                </div>
                <div class="panel-body">                   
                    {!! Form::open(['method'=>'POST','accept-charset'=>'UTF-8','files'=>true, 'id' => 'spreadsheetfrm']) !!}
                    <?php 
                    $spreadsheetData = Session::get('spreadsheetData');
                    $columns = [];
                    $columns['-1'] = 'Select a column title in the file'; 
                    foreach ($spreadsheetData[0] as $key => $value) {
                        if ($value != null) {
                            $columns[$key] = $value;
                        }
                    }    
                    ?>
                    <div class='form-row row'>
                        <div class='col-xs-12 form-group required'>
                            <div class="form-group">
                                {!! Form::label('order_number', 'Order number*', ['class' => 'control-label']) !!}
                                {!! Form::select('order_number', $columns, false, ['class' => 'form-control required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Tracking_number', 'Tracking number', ['class' => 'control-label']) !!}
                                {!! Form::select('tracking_number', $columns, false, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('tracking_url', 'Tracking URL', ['class' => 'control-label']) !!}
                                {!! Form::select('tracking_url', $columns, false, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('tracking_company', 'Tracking Company', ['class' => 'control-label']) !!}
                                {!! Form::select('tracking_company', $columns, false, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block" type="submit">Next</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#spreadsheetfrm').submit(function (e) {
            var validation = 0;
            $('.required').each(function(){
                var selfval = $(this);
                if (selfval.val() == '-1') {
                    validation = 1;
                    selfval.css('border', '1px solid red');
                }
            });
            if (validation == 1) {
                return false;
            }else{
                return true;
            }            
        });
    });
</script>
@endsection