<!-- Main content -->
{!! Form::model('', ['url' => ['upload-payment-info'], 'id'=>'confirmInvoiceForm','files'=>true,'method'=>'POST']) !!}
{!! Form::hidden('invoice_id',$invoice_id) !!}
<center><h1>Bank Wire Info or Zelle Info</h1></center>
<table class="table table-hover">
    <tr>
        <td class="text-right" colspan="2"><a class="btn btn-primary" href="{{ url('how-to-pay') }}">How to Pay</a></td>
    </tr>
    <tr>
        <th>Bank Wire Info</td>
        <td>Swift Code: CHASUS33<br><br>
            Account Number: 500592180<br>
            Routing Number: 021000021<br>
            Name: Dropship Agent Co<br><br>
            Address: 26411 Ynez Rd<br>
            Temecula, CA  92591<br>
            United States </td>
    </tr>
    <tr>
        <th>Zelle Info</td>
        <td>support@dropshipagent.com </td>
    </tr>
    <tr>
        <td class="text-center" colspan="2"><h2>Please attach photo of payment receipt here:</h2></td>
    </tr>
    <tr>
        <td class="text-center" colspan="2"><input type="file" name="payment_image" required=""></td>
    </tr>
    <tr>
        <td class="text-center" colspan="2"><button type="submit" class="btn btn-primary">Submit</button></td>
    </tr>
</table>
{!! Form::close() !!}
