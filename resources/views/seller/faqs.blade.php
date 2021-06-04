@extends('layouts.app')
@section('title', 'Dropship Agent Co. FAQ')
@section('main-content')

<!-- Main content -->
<section class="content textPage">
    <div class="">
        <div class="col-md-12 heading_sections">
            <h1 class="heading">FAQS</h1>
            <!-- <p class="subheading">Tracking</p> -->
        </div>
        <div class="faq-section">
            <div class="panel-group" id="accordion">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading active">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">How much does it cost to ship to a country outside of the US?</a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">
                            <div class="panel-body">
                                The shipping cost are depending on the weight and attributes of the products. The majority of our prices don't change unless the item is shipped to small countries that are harder to deliver to. If you apply for a quote or send us an email at support@dropshipagent.co we would be more than happy to let you know.


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">When and How Can I Get the Tracking Number and Dispatching Time?</a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                            <div class="panel-body">
                                Normally, we will enter the tracking number for your customers and ship them within two working days after your payment is received.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Can I Get a Better Offer If Had a Long Term Cooperation?</a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse">
                            <div class="panel-body">
                                Yes, we offer you price drops if your order quantity or amount is big enough and keeps scaling in the future.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">Am I able to get custom packaging?</a>
                            </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse">
                            <div class="panel-body">
                                Yes! You can also ask to upgrade certain packaging to your desire for the product if asked.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">Am I able to make changes to the current packaging?</a>
                            </h4>
                        </div>
                        <div id="collapseFive" class="panel-collapse collapse">
                            <div class="panel-body">
                                Yes! You can also ask to remove certain labels, translate instructions make small changes to the current packaging.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">How Can I Place a Dropshipping Order? </a>
                            </h4>
                        </div>
                        <div id="collapseSix" class="panel-collapse collapse">
                            <div class="panel-body">
                                If you like the price we quoted and are accepted, then orders to us can be extracted through, our app. All clients will see Invoices and payments will be made there as well.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">Will You Send Invoice or Receipts along with the Parcel?</a>
                            </h4>
                        </div>
                        <div id="collapseSeven" class="panel-collapse collapse">
                            <div class="panel-body">
                                No, we won't. Normally we only send items without invoice and receipts.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEight">Do You Provide White Label/Branding Service?</a>
                            </h4>
                        </div>
                        <div id="collapseEight" class="panel-collapse collapse">
                            <div class="panel-body">
                                Yes, we do. Please contact us at <a href="mailto:support@dropshipagent.co">support@dropshipagent.co.</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseNine">Do Parcels Get Lost During the Delivery?</a>
                            </h4>
                        </div>
                        <div id="collapseNine" class="panel-collapse collapse">
                            <div class="panel-body">
                                If some uncontrollable factors lead to the order missing by DHL or ePacket with proof to show our team we will resend the order again to your customer.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTen">What If a Customer Receives a Broken Delivery?</a>
                            </h4>
                        </div>
                        <div id="collapseTen" class="panel-collapse collapse">
                            <div class="panel-body">
                                If a customer receives a broken item when delivered they will need to show proof to the store owner. If the store owner can show our team we can determine if we can send out another for free.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEleven">How much is the Service Fee and APP, What is your charges?</a>
                            </h4>
                        </div>
                        <div id="collapseEleven" class="panel-collapse collapse">
                            <div class="panel-body">
                                We charge our dropsuppliers the product cost plus a 70% fee of what we save them from Aliexpress. Anything under $2 saved is a flat fee of $1. Also, our APP to use is $300 to have access. We look for serious dropsuppliers for our prestigious services to scale and have their business automated with less worries to have their business run more efficiently.
                                Ex: We save you $2 per unit we fee $1.40 per unit.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwelve">How to Place Sample on Dropship Agent Co?</a>
                            </h4>
                        </div>
                        <div id="collapseTwelve" class="panel-collapse collapse">
                            <div class="panel-body">
                                Sample orders can be placed being a client at Dropship Agent Co if you contact us for a sample.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThirteen">Can I have Multiple stores under the same account?</a>
                            </h4>
                        </div>
                        <div id="collapseThirteen" class="panel-collapse collapse">
                            <div class="panel-body">
                                No, each store has to have their own account.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<!-- /.content -->
@endsection


@section('script')
<script>
    $('.panel-collapse').on('show.bs.collapse', function() {
        $(this).siblings('.panel-heading').addClass('active');
    });

    $('.panel-collapse').on('hide.bs.collapse', function() {
        $(this).siblings('.panel-heading').removeClass('active');
    });
</script>
@endsection