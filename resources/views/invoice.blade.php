@extends('master') 

@section('urlTitles')
<?php session(['title' => 'Payments']);
session(['subtitle' => '']); ?>
@endsection

@section('content')
<link href="/css/invoice-print.min.css" rel="stylesheet" />
 
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb hidden-print pull-right">
				<li><a href="javascript:;">Home</a></li>
				<li class="active">Invoice</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Invoice <small> </small></h1>
			<!-- end page-header -->
			
			<!-- begin invoice -->
			<div class="invoice">
                <div class="invoice-company">
                    <span class="pull-right hidden-print">
                        <a href="{!! action('StudentsController@profile', base64_encode($invoice->student_id)) !!}" class="btn btn-sm btn-success m-b-10"><i class="fa fa-arrow-left"></i> Student Profile</a>
                    <a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-success m-b-10"><i class="fa fa-print m-r-5"></i> Print</a>
                    </span>
                    <img src="/img/logo.jpg" width="65" height="65" alt=""> {{$branch->name}}
                </div>
                <div class="invoice-header">
                    <div class="invoice-from">
                         
                        <address class="m-t-5 m-b-5">
                            Phone: {{$branch->tel}}<br />
                             {{$branch->email}}<br />
                            PB No: {{$branch->pobox}}<br />
                             
                        </address>
                    </div>
                    
                    <div class="invoice-date">
                        <small>{{$invoice->dated}}</small>
                        <div class="date m-t-5">Receipt# {{$invoice->invoice_no}}</div>
                        <div class="invoice-detail">                            
                             @if($invoice->cheque==1) Cheque-{{$invoice->cheque_no}} @elseif($invoice->card==1) Card @else Cash @endif<br />
                            
                        </div>
                    </div>
                </div>
                <div class="invoice-content">
                    <div class="table">
                        <table class="table table-invoice">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ITEM / EVENT</th>
                                    <th>RECIEVED / NOT</th>
                                    <th>UNIT COST</th>
                                    <th>QUANTITY</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody><?php $i=1; ?>
                                @if($invoice->subscriptions_amount>0)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>Subscriptions Pay</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>{{$invoice->subscriptions_amount}}</td>
                                </tr>
                                <?php $i++;?>
                                @endif
                                @foreach($events AS $event)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$event->item_name}}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>{{$event->total_amount}}</td>
                                </tr>
                                <?php $i++;?>
                                @endforeach

                                @foreach($items AS $item)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$item->item_name}}</td>
                                    <td>{{$item->quantity_received}}/{{$item->quantity_not_received}}</td>
                                    <td>{{$item->unit_price}}</td>
                                    <td>{{$item->qty}}</td>
                                    <td>{{$item->total_amount}}</td>
                                </tr>
                                <?php $i++;?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="invoice-price">
                        <div class="invoice-price-left">
                            <div class="invoice-price-row">
                                <div class="sub-price">
                                    <small>SUBTOTAL</small>
                                    AED {{$invoice->amount_paid-$invoice->service_charge+$invoice->discount}}
                                </div>
                                @if($invoice->service_charge)
                                <div class="sub-price">
                                    <i class="fa fa-plus"></i>
                                </div>
                                <div class="sub-price">
                                    <small>SERVICE CHARGE</small>
                                    AED {{$invoice->service_charge}}
                                </div>
                                @endif
                                @if($invoice->discount)
                                <div class="sub-price">
                                    <i class="fa fa-minus"></i>
                                </div>
                                <div class="sub-price">
                                    <small>DISCOUNT</small>
                                    AED {{$invoice->discount}}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="invoice-price-right">
                            <small>TOTAL</small> AED {{$invoice->amount_paid}}
                        </div>
                    </div>
                </div>
                <div class="invoice-note">
                    * Issued By: {{$invoice->issued}}, Printed Date  :{{$today}}<br />
                    * Issued For {{ucwords(strtolower($invoice->full_name))}} ({{$invoice->student_id}})<br/>
                    * Received store items and events fee won't be refunded. <br/>
                    * Subscription fee will be refunded as per the policy descibed in the registration form.
                </div>
                <div class="invoice-footer text-muted">
                    <p class="text-center m-b-5">
                         
                    </p>
                    <p class="text-right">
                        <span class="m-r-10">  <strong>Accountant</strong></span>
                        <span class="m-r-10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span class="m-r-10">   <strong>Cashier</strong></span>
                    </p>
                     
                </div>
            </div>
			<!-- end invoice -->
		</div> @endsection