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
        {!! Form::model($user, ['url' => ['my-account'], 'id'=>'flagRecForm','files'=>true,'method'=>'POST']) !!}
        <div class="col-md-12">
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
                <div class="box-primary box-primary-box">
                    <!-- form start -->
                    <div class="box-body">
                        <div class="col-md-12">
                            <h1 class="heading"><i class="fa fa-user-o" aria-hidden="true"></i> My Account</h1>
                        </div>
                        @if (auth()->user()->status < 0)
                        <div class="col-md-12">
                            <br>
                            Thank you for choosing Dropship Agent as your dropshipping partner. <br><br>Please fill out the form below with details regarding the products you want sourced. Our team will check your requirements and if we can provide you better deals on your chosen products, we will notify you of the product approval and the next steps in the process.<br><br>
                        </div>
                        @else
                        <div class="col-md-12">
                            <h4>Manage exports and account setting</h4><br>
                        </div>
                        @endif
                        <div class="col-md-6 form-group">
                            <label for="" class="required-field"><i class="fa fa-user-o" aria-hidden="true"></i> Store Name:</label>
                            {!! Form::text('name', null, array('placeholder' => 'Full Name','class' => 'form-control', 'required' => 'required')) !!}

                        </div>
                        <div class="col-md-6 form-group">
                            <label for="" class="required-field"><i class="fa fa-envelope-o" aria-hidden="true"></i> Email:</label>
                            {!! Form::email('email', null, array('placeholder' => 'Email','class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                        <div class="col-md-6 form-group">
                            <label for=""><i class="fa fa-money" aria-hidden="true"></i> Select Currency:</label>
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
                        <div class="col-md-6 form-group phone_div">
                            <label for="phone"><i class="fa fa-volume-control-phone" aria-hidden="true"></i> Contact Number:</label>
                            {!! Form::tel('phone', null, array('placeholder' => 'Contact Number','class' => 'form-control','id' => 'phone', 'required' => 'required')) !!}
                            {!! Form::hidden('phone_code', null, array('id' => 'phone_code')) !!}
                            {!! Form::hidden('iso2', null, array('id' => 'iso2')) !!}
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for=""><i class="fa fa-flag-o" aria-hidden="true"></i> City:</label>
                                    {!! Form::text('city', null, array('placeholder' => 'City','class' => 'form-control city', 'required' => 'required')) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><i class="fa fa-flag-o" aria-hidden="true"></i> State:</label>
                                    {!! Form::text('state', null, array('placeholder' => 'State','class' => 'form-control state', 'required' => 'required')) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><i class="fa fa-flag-o" aria-hidden="true"></i> Country:</label>
                                    {!! Form::text('country', null, array('placeholder' => 'Country','class' => 'form-control country_text', 'required' => 'required')) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><i class="fa fa-code" aria-hidden="true"></i> Zip Code:</label>
                                    {!! Form::text('zip_code', null, array('placeholder' => 'Zip Code','class' => 'form-control zip_code', 'required' => 'required')) !!}
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for=""><i class="fa fa-address-card-o" aria-hidden="true"></i> Address</label>
                                    {!! Form::text('address', null, array('placeholder' => 'Address', 'class' => 'form-control address')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="checkbox" id="same_address" name="is_same_address" value="1" @if($user->is_same_address){{ 'checked' }}@endif>
                            <label for="same_address"> Billing address same as address</label></div>
                        <div class="form-group billing_address_fields" {!! ($user->is_same_address)?'style="display:none;"':"" !!}>
                            <div class="col-md-6 form-group">
                                <label for=""><i class="fa fa-flag-o" aria-hidden="true"></i> City:</label>
                                {!! Form::text('billing_city', null, array('placeholder' => 'City','class' => 'form-control billing_city', 'required' => 'required')) !!}
                            </div>
                            <div class="col-md-6 form-group">
                                <label for=""><i class="fa fa-flag-o" aria-hidden="true"></i> State:</label>
                                {!! Form::text('billing_state', null, array('placeholder' => 'State','class' => 'form-control billing_state', 'required' => 'required')) !!}
                            </div>
                            <div class="col-md-6 form-group">
                                <label for=""><i class="fa fa-flag-o" aria-hidden="true"></i> Country:</label>
                                {!! Form::text('billing_country', null, array('placeholder' => 'Country','class' => 'form-control billing_country', 'required' => 'required')) !!}
                            </div>
                            <div class="col-md-6 form-group">
                                <label for=""><i class="fa fa-code" aria-hidden="true"></i> Zip Code:</label>
                                {!! Form::text('billing_zip_code', null, array('placeholder' => 'Zip Code','class' => 'form-control billing_zip_code', 'required' => 'required')) !!}
                            </div>
                            <div class="col-md-12 form-group">
                                <label for=""><i class="fa fa-address-card-o" aria-hidden="true"></i> Billing Address</label>
                                {!! Form::text('billing_address', null, array('placeholder' => 'Billing Address', 'class' => 'form-control billing_address', 'required' => 'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for=""><i class="fa fa-file-excel-o"></i> Start my order exports from order number:</label>
                            {!! Form::text('export_orders_from', null, array('placeholder' => 'Ex.: #1001','class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                </div>
            </div>
            <br>



            <div class="export_shadule">

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
                    <!-- <label for="daily">Export Daily: {!! Form::checkbox('cron_options[]', 'daily', $Ddaily, ['class' => 'daily', 'id' => 'daily']) !!} </label> -->
                    <div class="row">
                        <div class="col-md-3">
                            <label class="custom_checkbox_container" for="daily">
                                <i class="fa fa-calendar"></i>
                                Export Daily: {!! Form::checkbox('cron_options[]', 'daily', $Ddaily, ['class' => 'daily', 'id' => 'daily']) !!}
                                <input type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 form-group">
                    <div class="all_day_box" style="<?php echo ($Ddaily == true) ? 'display:none' : ''; ?>">
                        <div class="col-md-12 form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="custom_checkbox_container" for="monday">
                                        <i class="fa fa-calendar"></i>
                                        {!! Form::checkbox('cron_options[]', 'monday', $Dmonday, ['class' => 'all_day', 'id' => 'monday']) !!} Monday                                                
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label class="custom_checkbox_container" for="tuesday">
                                        <i class="fa fa-calendar"></i>
                                        {!! Form::checkbox('cron_options[]', 'tuesday', $Dtuesday, ['class' => 'all_day', 'id' => 'tuesday']) !!} Tuesday
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label class="custom_checkbox_container" for="wednesday">
                                        <i class="fa fa-calendar"></i>
                                        {!! Form::checkbox('cron_options[]', 'wednesday', $Dwednesday, ['class' => 'all_day', 'id' => 'wednesday']) !!} Wednesday
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label class="custom_checkbox_container" for="thursday">
                                        <i class="fa fa-calendar"></i>
                                        {!! Form::checkbox('cron_options[]', 'thursday', $Dthursday, ['class' => 'all_day', 'id' => 'thursday']) !!} Thursday
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label class="custom_checkbox_container" for="friday">
                                        <i class="fa fa-calendar"></i>
                                        {!! Form::checkbox('cron_options[]', 'friday', $Dfriday, ['class' => 'all_day', 'id' => 'friday']) !!} Friday
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label class="custom_checkbox_container" for="saturday">
                                        <i class="fa fa-calendar"></i>
                                        {!! Form::checkbox('cron_options[]', 'saturday', $Dsaturday, ['class' => 'all_day', 'id' => 'saturday']) !!} Saturday
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label class="custom_checkbox_container" for="sunday">
                                        <i class="fa fa-calendar"></i>
                                        {!! Form::checkbox('cron_options[]', 'sunday', $Dsunday, ['class' => 'all_day', 'id' => 'sunday']) !!} Sunday
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
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
                <div class="col-md-12 box-footer-f">
                    @if (auth()->user()->status < 0)
                    <button type="button" id="" class="btn btn-danger sendFlagRec">Send Request</button> 
                    @else
                    <button type="button" class="btn btn-warning btn-warning-btn updateProfileBtn">Update Profile</button>
                    @endif
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</section>
<!-- /.content -->

<script type="text/javascript">
    $(document).ready(function () {

        //action on is billing address same checkbox check or uncheck
        $(document).on('change', '#same_address', function (e) {
            if (this.checked) {
                $('.billing_city').val($('.city').val());
                $('.billing_state').val($('.state').val());
                $('.billing_country').val($('.country_text').val());
                $('.billing_zip_code').val($('.zip_code').val());
                $('.billing_address').val($('.address').val());
                $('.billing_address_fields').hide();
            } else {
                $('.billing_address_fields').show();
            }
        });

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
                $('.aliexpress_url_' + productID).addClass("check_valid_url");
                $('.orders_per_day_' + productID).attr("required", true);
                $('.product_item_' + productID).show();
            } else {
                $('.aliexpress_url_' + productID).attr("required", false);
                $('.aliexpress_url_' + productID).removeClass("check_valid_url");
                $('.orders_per_day_' + productID).attr("required", false);
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
            if(phoneVal != ''){
                if (!Number(phoneVal)) {
                alert("Please enter valid phone number");
                return false;
            }
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
                var checkValidURL = false;
                var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                        '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                        '(\\?[;&a-z\\d%_.~+=-]*)?', 'i'); // fragment locator
                $('[required]').each(function () {
                    if ($(this).val() == '') {
                        requiredField = true;
                    }
                });
                $('.check_valid_url').each(function () {
                    if (pattern.test($(this).val()) == false) {
                        checkValidURL = true;
                    }
                });
                // if (checkValidURL == true) {
                //     alert("Please enter valid URL in product URL");
                //     return false;
                // }

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