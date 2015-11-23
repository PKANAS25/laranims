@extends('master') 

@section('urlTitles')
<?php session(['title' => 'Store']);
session(['subtitle' => 'nonReceived']); ?>
@endsection

@section('content')
<link href="/css/invoice-print.min.css" rel="stylesheet" />
 
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb hidden-print pull-right">
				<li><a href="javascript:;">Store</a></li>
				<li class="active">Receive Letter</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Receive Letter <small> </small></h1>
			<!-- end page-header -->
			
			<!-- begin invoice -->
			<div class="invoice">
                <div class="invoice-company">
                    <span class="pull-right hidden-print">
                         
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
                        <small>{{$trackItem->dated}}</small>
                        <div class="date m-t-5">Item from Receipt# {{$trackItem->invoice_no}}</div>
                        <div class="invoice-detail">                            
                            
                        </div>
                    </div>
                </div>
                <div class="invoice-content">
                    <div class="table">
                        <table class="table table-invoice">
                            <thead>
                                <tr> 
                                    <th>ITEM</th>
                                    <th>RECIEVED</th>
                                     
                                </tr>
                            </thead>
                            <tbody>  
                                <tr> 
                                    <td>{{$trackItem->item_name}}</td>
                                    <td>{{$trackItem->qty}}</td>
                                     
                                    
                                </tr> 
                                 
                            </tbody>
                        </table>
                    </div>
                     
                </div>
                <div class="invoice-note">
                    Above listed items are received.
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
		</div>@endsection