@extends('layouts.app')
@section('title', 'Invoice List')
@section('main-content')
<!-- Content Header (Page header) -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="col-md-12 heading_sections">
            <!-- <h1 class="heading">Dropship <span style="color: #FFF;">Agent</span></h1> -->
            <img src="{{ asset('img/dropship.png') }}" class="logo-dropship">
            <p class="subheading"></p>
        </div>
    <div class="col-md-12 nav-tabs-custom invoices_page" style="box-shadow: none;">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#unpaid_invoice" id="unpaid_invoice_tab" data-toggle="tab">Unpaid</a></li>
            <li><a href="#paid_invoice" data-toggle="tab" id="paid_invoice_tab">Paid</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active table-responsive" id="unpaid_invoice">
                <table class="table table-hover table-striped table-bordered datatable" id="unpaid_invoice_table">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Order Value</th>
                            <th>Other Charges</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                </table>
            </div>
            <div class="tab-pane table-responsive" id="paid_invoice">
                <table class="table table-hover table-striped table-bordered datatable" id="paid_invoice_table">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Order Value</th>
                            <th>Other Charges</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- /.tab-content -->
    </div>
</section>

<!-- Modal -->
<div id="payNowModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Pay Now</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="content">
                        <form class="" action="{{ url('/invoice_checkout') }}" method="post">
                            {{ csrf_field() }}
                            {!! Form::hidden('invoiceID', null, array('class' => 'invoice_id')) !!}
                            {!! Form::hidden('camount', null, array('class' => 'invoice_amount')) !!}
                            @if(count($userCardProfiles)>0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Card</th>
                                            <th>Ending With</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userCardProfiles as $key => $val)
                                        <tr>
                                            <td><label for="card_type{{ $val['item_profile_id'] }}">{!! Form::radio('payment_option', $val['item_profile_id'], false, ['class' => 'select_card', 'id' => 'card_type'.$val['item_profile_id']]) !!} </label></td>
                                            <td>{{ $val['card_type'] }}</td>
                                            <td>{{ $val['card_4_digit'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            <label for="custom_card">{!! Form::checkbox('card_option', 'custom_card', false, ['class' => 'custom_card', 'id' => 'custom_card']) !!} Add new card</label><br/><br/>
                            <div class="addNewCard" {{ (count($userCardProfiles) > 0)?"style=display:none;":"" }}>
                                <h3>Credit Card Information</h3>
                                <div class="form-group">
                                    <label for="cnumber">Card Number</label>
                                    <input type="text" class="form-control cnumber" id="cnumber" name="cnumber" placeholder="Enter Card Number">
                                </div>
                                <div class="form-group">
                                    <label for="card-expiry-month">Expiration Month</label>
                                    {{ Form::selectMonth(null, null, ['name' => 'card_expiry_month', 'class' => 'form-control card_expiry_month']) }}
                                </div>
                                <div class="form-group">
                                    <label for="card-expiry-year">Expiration Year</label>
                                    {{ Form::selectYear(null, date('Y'), date('Y') + 10, null, ['name' => 'card_expiry_year', 'class' => 'form-control card_expiry_year']) }}
                                </div>
                                <div class="form-group">
                                    <label for="ccode">Card Code</label>
                                    <input type="text" class="form-control ccode" id="ccode" name="ccode" placeholder="Enter Card Code">
                                </div>
                            </div>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- (c) 2005, 2020. Authorize.Net is a registered trademark of CyberSource Corporation --> <div style="margin-left: 30px; clear: both;" class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id = "f3bc89f6-aaf8-4c87-b3c6-65b911c74055";</script> <script type="text/javascript" language="javascript" src="https://verify.authorize.net/anetseal/seal.js" ></script> </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
                    $(document).ready(function () {
                        //show modal popup jquery
                        $(document).on('click', '.pay_now_btn', function (e) {
                            $('.invoice_id').val($(this).data("id"));
                            $('.invoice_amount').val($(this).data("val"));
                            // show Modal
                            $('#payNowModal').modal('show');
                        });

                        var unpaid_invoice_table = $('#unpaid_invoice_table').DataTable({
                            "responsive": true,
                            "bProcessing": true,
                            "serverSide": true,
                            "ordering": true,
                            "order": [[0, "desc"]],
                            "ajax": {
                                url: "",
                                data: function (d) {
                                    d.paid_status = "0";
                                },
                                error: function (error) {
                                    console.log(error);
                                    alert('Something went wrong');
                                }
                            },
                            "aoColumns": [
                                {mData: 'id'},
                                {mData: 'created_at'},
                                {mData: 'admin_price_total'},
                                {mData: 'other_charges'},
                                {mData: 'action'},
                            ],
                            "aoColumnDefs": [
                                {"bSortable": false, "aTargets": ['action']}
                            ],
                            "language": {
                                "zeroRecords": "No invoice available",
                                "paginate": {
                                    "previous": "< ",
                                    "next": " >"
                                }
                            }
                        });
                        var paid_invoice_table = $('#paid_invoice_table').DataTable({
                            "responsive": true,
                            "bProcessing": true,
                            "serverSide": true,
                            "ordering": true,
                            "order": [[0, "desc"]],
                            "ajax": {
                                url: "",
                                data: function (d) {
                                    d.paid_status = "1";
                                },
                                error: function (error) {
                                    console.log(error);
                                    alert('Something went wrong');
                                }
                            },
                            "aoColumns": [
                                {mData: 'id'},
                                {mData: 'store_domain'},
                                {mData: 'admin_price_total'},
                                {mData: 'other_charges'},
                                {mData: 'action'},
                            ],
                            "aoColumnDefs": [
                                {"bSortable": false, "aTargets": ['action']}
                            ],
                            "language": {
                                "zeroRecords": "No invoice available",
                                "paginate": {
                                    "previous": "< ",
                                    "next": " >"
                                }
                            }
                        });
                        $('#unpaid_invoice_tab').click(function () {
                            unpaid_invoice_table.draw();
                        });
                        $('#paid_invoice_tab').click(function () {
                            paid_invoice_table.draw();
                        });

                        jQuery('.select_card').change(function () {
                            if (jQuery('.custom_card').is(':checked')) {
                                jQuery('.custom_card').trigger("click");
                            }
                        });
                        jQuery('.custom_card').change(function () {
                            if (jQuery(this).is(':checked')) {
                                jQuery(".select_card").each(function () {
                                    jQuery(this).prop("checked", false);
                                });
                                jQuery('.cnumber').prop("required", true);
                                jQuery('.card_expiry_month').prop("required", true);
                                jQuery('.card_expiry_year').prop("required", true);
                                jQuery('.ccode').prop("required", true);
                                jQuery('.addNewCard').show();
                            } else {
                                jQuery('.cnumber').prop("required", false);
                                jQuery('.card_expiry_month').prop("required", false);
                                jQuery('.card_expiry_year').prop("required", false);
                                jQuery('.ccode').prop("required", false);
                                jQuery('.addNewCard').hide();
                            }
                        });

                    });
</script>
<!-- /.content -->
@endsection