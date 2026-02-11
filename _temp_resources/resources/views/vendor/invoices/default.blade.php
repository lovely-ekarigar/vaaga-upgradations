<!DOCTYPE html>
<html dir="ltr">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->name }}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        h1, h2, h3, h4, span, p, div {
            font-family: DejaVu Sans;
        }
        .f20{
            font-size: 12px !important;
        }

tr:nth-child(odd) {background-color: #FFE599;}
    </style>
</head>
<body>
    <div style="text-align: center;text-decoration: underline;font-weight: 700;margin-bottom: 15px;">
        INVOICE
        <br>
        <br>
        <br>
    </div>
<div style="display: block;clear: both">
    <div style="float: left; width:200pt;">
        <img class="img-rounded" height="90px"
             src="https://www.vaagaacademy.com/newassets/img/logo.png">
    </div>
    <div style="float: right;width: 230pt;">
        <h5>Date: <b> {{ $invoice->date->formatLocalized('%A %d %B %Y') }}</b></h5>
        @if ($invoice->number)
            <h5>Invoice #: <b>{{strval($invoice->number) }}</b></h5>
        @endif
        <h5>Order Reference No: <b>ORD-{{$invoice->number }}</b></h5>
         @if ($invoice->month)
        <h5>Billing Month: <b>{{$invoice->month }}</b></h5>
        @endif
    </div>
</div>
<div style="display: inline-block;clear: both;width: 100%;">
   
    <div style="width:260pt; float:left;">
        <h4>Business Details:</h4>
        <div class="panelx panel-defaultx">
            <div class="panel-body">
                @if(config('contact_data') != "")
                    @php
                        $contact_data = contact_data(config('contact_data'));
                    @endphp
                    <h4 style="font-weight: bold;">VaaGa Academy</h4>

                  
                        <span>E-404, Microtek Greenburg, Sector-86 </span><br>
                        <span>Gurugram, Haryana – 122004 </span><br>
                   


                  
                        <span style="font-family: Helvetica, Arial, sans-serif;">Website: www.vaagaacademy.com</span>
                        <br>
                   

                  
                        <span> Email: info@vaagaacademy.com </span><br>
                    
                @else
                    <i>No business details</i><br/>
                @endif
            </div>
        </div>
    </div>
    <div style="float: right; width:240pt;height: auto;display: inline-block;margin-top:40px;">
        <h4>Customer Details:</h4>
        <div class="panelx panel-defaultx" style="padding: 15px;padding-top: 0px">
            {!! $invoice->customer_details->count() == 0 ? '<i>No customer details</i><br />' : '' !!}
            <span>Name :</span> {{ $invoice->customer_details->get('name') }}<br>
            <span>Email :</span> {{ $invoice->customer_details->get('email') }}<br>
            <span>Contact No :</span> {{ $invoice->customer_details->get('phone') }}
        </div>
    </div>
</div>

<div style="clear:both;display: block;">
    <h4>Items:</h4>
    <table class="table table-bordered">
        <thead style="background:#ff9800">
        <tr>
            <th>S.No.</th>
            <th>Course Name</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($invoice->items as $key=>$item)
            @php $key++ @endphp
            <tr>
                <td>{{$key}}</td>
                <td style=" font-family: DejaVu Sans;">{{ $item->get('name') }}</td>
                <td class="text-right">₹{{ $item->get('totalPrice') }} </td>
            </tr>
        @endforeach

        <tr>
                    <td colspan="2" class="text-right f20"><b>Subtotal</b></td>
                    <td class="text-right">₹{{ $invoice->subTotalPriceFormatted() }} </td>
                </tr>
                 @if($invoice->taxData != null)
                    <tr>
                        <!--<td colspan="2" class="text-right f20"><b>  GST(18%)</b></td>-->
                        <td colspan="2" class="text-right f20"><b>Convenience Fee</b></td>
                        <td class="text-right">+ ₹{{$invoice->taxData }} </td>
                    </tr>
                @endif
                @if($invoice->discount)
                    <tr>
                        <td colspan="2" class="text-right f20"><b>  Discount</b></td>
                        <td class="text-right">- ₹{{$invoice->discount }} </td>
                    </tr>
                @endif
                 

                <tr>
                    <td colspan="2" class="text-right f20"><b>TOTAL</b></td>
                    <td class="text-right"><b>₹{{ $invoice->total }} </b></td>
                </tr>

        </tbody>
    </table>
    <div style="clear:both; position:relative;">
<br>
        <p class="text-center">If you have any questions concerning this invoice, please contact info@vaagaacademy.com
</p>
<p class="text-center">
Thank you for choosing <strong>VaaGa Academy!</strong>
</p>
    </div>

<div style="background:#ff9800;padding:5px 5px; text-align: center;">
    Contact us +91 98203 80469
</div>

</div>



@if ($invoice->footnote)
    <br/><br/>
    <div class="well">
        {{ $invoice->footnote }}
    </div>
@endif
</body>
</html>
