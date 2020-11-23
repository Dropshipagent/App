@extends('layouts.app')
@section('title', 'My Account')
@section('main-content')

<style type="text/css">
    .iti__flag {background-image: url("intlTelInput/img/flags.png");}

    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .iti__flag {background-image: url("intlTelInput/img/flags@2x.png");}
    }
</style>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="register-box-body">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                {!! Form::model($user, ['url' => ['my-account'], 'id'=>'flagRecForm','files'=>true,'method'=>'POST']) !!}
                <div class="box box-primary box-primary-box">
                    <!-- form start -->
                    <div class="box-body">
                        @if (auth()->user()->status < 0)
                        <div class="col-md-12 text-center">
                            Thank you for choosing Dropship Agent as your dropshipping partner. <br><br>Please fill out the form below with details regarding the products you want sourced. Our team will check your requirements and if we can provide you better deals on your chosen products, we will notify you of the profile approval and the next steps in the process.<br><br>
                        </div>
                        @endif
                        <div class="col-md-6 form-group">
                            <label for="">Store Name:</label>
                            {!! Form::text('name', null, array('placeholder' => 'Full Name','class' => 'form-control', 'required' => 'required')) !!}

                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Email:</label>
                            {!! Form::email('email', null, array('placeholder' => 'Email','class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Select Currency:</label>
                            <select  class="form-control" name="currency_code">
                                @foreach($currencies_list as $currency)
                                <?php
                                if ($user->currency_code == $currency->code) {
                                    $sel = "selected";
                                } else {
                                    $sel = "";
                                }
                                ?>
                                <option value="{{$currency->code}}" {{$sel}}>{{$currency->name.' - '.$currency->code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="phone">Contact Number:</label>
                            {!! Form::tel('phone', null, array('placeholder' => 'Contact Number','class' => 'form-control','id' => 'phone', 'required' => 'required')) !!}
                            {!! Form::hidden('phone_code', null, array('id' => 'phone_code')) !!}
                            {!! Form::hidden('iso2', null, array('id' => 'iso2')) !!}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">City:</label>
                            {!! Form::text('city', null, array('placeholder' => 'City','class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">State:</label>
                            {!! Form::text('state', null, array('placeholder' => 'State','class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Country:</label>
                            {!! Form::text('country', null, array('placeholder' => 'Country','class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Zip Code:</label>
                            {!! Form::text('zip_code', null, array('placeholder' => 'Zip Code','class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Billing Address</label>
                            {!! Form::textarea('billing_address', null, array('placeholder' => 'Billing Address', 'rows' => 2,'class' => 'form-control')) !!}
                        </div>
                        @if (auth()->user()->status > 1)
                        <div class="col-md-12 form-group">
                            <h4>Schedule order export</h4><br>
                            <?php
                            $cronOption = json_decode($user->cron_options);
                            $Ddaily = (isset($cronOption->daily)) ? true : false;
                            $Dmonday = (isset($cronOption->monday)) ? true : false;
                            $Dtuesday = (isset($cronOption->tuesday)) ? true : false;
                            $Dwednesday = (isset($cronOption->wednesday)) ? true : false;
                            $Dthursday = (isset($cronOption->thursday)) ? true : false;
                            $Dfriday = (isset($cronOption->friday)) ? true : false;
                            $Dsaturday = (isset($cronOption->saturday)) ? true : false;
                            $Dsunday = (isset($cronOption->sunday)) ? true : false;
                            ?>
                            <label for="daily">Export Daily: {!! Form::checkbox('cron_options[]', 'daily', $Ddaily, ['class' => 'daily', 'id' => 'daily']) !!} </label><br/>
                        </div>
                        <div class="col-md-10 form-group">
                            <div class="all_day_box" style="<?php echo ($Ddaily == true) ? 'display:none' : ''; ?>">
                                <div class="col-md-6 form-group">
                                    <label for="monday">{!! Form::checkbox('cron_options[]', 'monday', $Dmonday, ['class' => 'all_day', 'id' => 'monday']) !!} Export every Monday</label><br/>
                                    <label for="tuesday">{!! Form::checkbox('cron_options[]', 'tuesday', $Dtuesday, ['class' => 'all_day', 'id' => 'tuesday']) !!} Export every Tuesday</label><br/>
                                    <label for="wednesday">{!! Form::checkbox('cron_options[]', 'wednesday', $Dwednesday, ['class' => 'all_day', 'id' => 'wednesday']) !!} Export every Wednesday</label><br/>
                                    <label for="thursday">{!! Form::checkbox('cron_options[]', 'thursday', $Dthursday, ['class' => 'all_day', 'id' => 'thursday']) !!} Export every Thursday</label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="friday">{!! Form::checkbox('cron_options[]', 'friday', $Dfriday, ['class' => 'all_day', 'id' => 'friday']) !!} Export every Friday</label><br/>
                                    <label for="saturday">{!! Form::checkbox('cron_options[]', 'saturday', $Dsaturday, ['class' => 'all_day', 'id' => 'saturday']) !!} Export every Saturday</label><br/>
                                    <label for="sunday">{!! Form::checkbox('cron_options[]', 'sunday', $Dsunday, ['class' => 'all_day', 'id' => 'sunday']) !!} Export every Sunday</label>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if (auth()->user()->status < 0)
                        <div class="col-md-12 products_list">
                            <a href="javascript:void(0)" class="btn btn-block btn-danger btn-sm btnSyncProducts">Sync Products</a>
                        </div>
                        @endif
                    </div><!-- /.box-body -->
                    <div class="box-footer box-footer-f">
                        @if (auth()->user()->status < 0)
                        <button type="button" id="" class="btn btn-danger sendFlagRec">Send Request</button> 
                        @else
                        <button type="button" class="btn btn-warning btn-warning-btn updateProfileBtn">Update Profile</button>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.form-box -->
        </div>
    </div>

</section>
<!-- /.content -->

<script type="text/javascript">
    $(document).ready(function () {
        //sync and show product list
        $.ajax({
            url: "{{ url('storeproducts/syncproducts', auth()->user()->username) }}",
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('.products_list').html(data);
            },
            error: function (xhr, status) {
                alert("Your store does not appear to have any products. Please add any product and try again.");
            },
            complete: function (xhr, status) {
                //$('#showresults').slideDown('slow')
            }
        });

        //action on checkbox check or uncheck
        $(document).on('change', '.flag_checkbox', function (e) {
            var productID = this.value;
            if (this.checked) {
                $('.aliexpress_url_' + productID).attr("required", true);
                $('.orders_per_day_' + productID).attr("required", true);
                $('.variants_you_sell_' + productID).attr("required", true);
                $('.countries_you_ship_' + productID).attr("required", true);
                $('.cost_per_unit_' + productID).attr("required", true);
                $('.product_item_' + productID).show();
            } else {
                $('.aliexpress_url_' + productID).attr("required", false);
                $('.orders_per_day_' + productID).attr("required", false);
                $('.variants_you_sell_' + productID).attr("required", false);
                $('.countries_you_ship_' + productID).attr("required", false);
                $('.cost_per_unit_' + productID).attr("required", false);
                $('.product_item_' + productID).hide();
            }
        });

        //form submit on profile update
        $('.updateProfileBtn').on('click', function () {
            var phoneVal = $('#phone').val();
            if (!Number(phoneVal)) {
                alert("Please enter valid phone number");
                return false;
            } else {
                $("#flagRecForm").submit();
            }
        });

        //submit form first time
        $('.sendFlagRec').on('click', function () {
            var phoneVal = $('#phone').val();
            if (!Number(phoneVal)) {
                alert("Please enter valid phone number");
                return false;
            }
            
            var validateData = false;
            $('.flag_checkbox').each(function () {
                if (this.checked) {
                    validateData = true;
                }
            });
            if (validateData == false) {
                alert("Please select atleast one product.");
                return false;
            } else {
                var requiredField = false;
                $('[required]').each(function () {
                    if ($(this).val() == '') {
                        requiredField = true;
                    }
                });
                if (requiredField == true) {
                    alert("Please fill all required fields");
                } else {
                    var r = confirm("Are you sure?");
                    if (r == true) {
                        $("#flagRecForm").submit();
                    }
                }
            }
        });
    });
</script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.daily').change(function () {
            if (jQuery(this).is(':checked')) {
                jQuery(".all_day").each(function () {
                    jQuery(this).prop("checked", false);
                });
                jQuery('.all_day_box').hide();
            } else {
                jQuery('.all_day_box').show();
            }
        });
    });
</script>
@endsection


@section('script')
<script src="{{ asset('js/intlTelInput.js') }}"></script>

<script type="text/javascript">

    $("#phone").intlTelInput({
        separateDialCode: true,
        nationalMode: true,
        formatOnDisplay: true,
        utilsScript: "{{ asset('js/utils.js') }}"
    });


    var phonenum = $("#phone").val();
    var phone_code = $("#phone_code").val();

    var val = phone_code + '' + phonenum;
    $("#phone").intlTelInput("setNumber", val);


    $("#phone").on("countrychange", function (e, countryData) {

        //console.log(countryData);
        //var asd = countryData.dialCode;
        //alert(asd);
        $("#phone_code").val('+' + countryData.dialCode);
        $("#iso2").val(countryData.iso2);
    });
</script>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/intlTelInput.css') }}" />
@endsection