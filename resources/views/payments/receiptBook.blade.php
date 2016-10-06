@extends('formsMaster') 

@section('urlTitles')
<?php session(['title' => 'Payments']);
session(['subtitle' => 'receipts']); ?>
@endsection


@section('content')
<link href="/css/other-print.min.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="/dist/msgbox/jquery.msgbox.css" />
<script type="text/javascript" src="/dist/msgbox/jquery.msgbox.min.js"></script> 


  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Receipts</a></li>
				<li class="active"><a href="javascript:;">List</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Receipts <small> List</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Receipt Book</h4>
                        </div>

                        <div class="panel-body">         
                                
                        <form class="form-inline hidden-print" name="eForm" id="eForm"  method="POST" autocomplete="OFF">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <div class="form-group m-r-10">
                                @if(Auth::user()->admin_type==2)
                                    <select class="form-control" id="select-required" name="branch" data-fv-notempty="true">
                                            <option value="">Please choose</option>
                                            @foreach($branches as $branch)
                                            <option value="{!! $branch->id !!}">{!! $branch->name !!}</option>
                                            @endforeach
                                        </select>
                                @else
                                <input type="hidden" name="branch" value="{{ Auth::user()->branch }}">    
                                @endif  
                                </div>
                                <div class="form-group m-r-10">
                                    <input class="form-control" type="text" placeholder="Start Date" id="start_date" name="start_date"  data-fv-notempty="true"  value="{{ old('start_date') }}" />
                                </div>
                                <div class="form-group m-r-10">
                                    <input class="form-control" type="text" placeholder="End Date" id="end_date" name="end_date"  data-fv-notempty="true"  value="{{ old('end_date') }}" />
                                </div>
                                 
                                <button type="submit" class="btn btn-primary m-r-5">Generate Report</button> 
                            </form> 
                             
                         </div>

                         @if($submit==1)
                        <div class="invoice-header onlyprintCenter">
                                    <div class="invoice-from" >
                                         
                                        <address class="m-t-5 m-b-5" align="center">
                                          <img src="/img/logo.jpg" width="65" height="65" alt=""> {{ $branchSelected->name }} Receipt Book
                                        </address>
                                    </div>
                                  
                                </div>
                        <div class="panel-body">  
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Receipt#</th>
                                        <th>Student</th>
                                        <th>Medium</th>
                                        <th>Subscription</th>
                                        <th>Item/Events</th>
                                        <th>Discount</th>
                                        <th>Service Charge</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receipts AS $receipt)
                                    <tr>
                                        <td><a title="Print receipt"  href="{!! action('InvoiceController@view', base64_encode($receipt->invoice_id) ) !!}" target="_blank">{{  $receipt->invoice_no }}</a></td>
                                        <td><a title="View Student Details" href="{!! action('StudentsController@profile', base64_encode($receipt->student_id)) !!}" target="_blank">{{  $receipt->full_name }}</a></td>
                                        <td> @if($receipt->cheque) {{  $receipt->cheque_no }} @elseif($receipt->card) Card @else Cash @endif</td>
                                        <td>{{  $receipt->subscriptions_amount }}</td>
                                        <td>{{ ($receipt->amount_paid - $receipt->service_charge)-$receipt->subscriptions_amount }}</td>
                                        <td>{{  $receipt->discount }}</td>
                                        <td>{{  $receipt->service_charge }}</td>
                                        <td>{{  $receipt->amount_paid }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                             <a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-success m-b-10 hidden-print"><i class="fa fa-print m-r-5"></i> Print</a>
                        </div> 
                        @endif
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>

    <script>
        $(document).ready(function() {
            App.init();

            $('#data-table').dataTable( {
                "paging":   false,
                "ordering": true,
                "info":     false,
                "aaSorting": [],
                "columnDefs": [ {
                      "targets": 'nosort',
                      "bSortable": false,
                      "searchable": false
                    } ]
            } );
                                             
      $('#start_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'start_date');
            });

            
            $('#end_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'end_date');
                });
                                             
            $('#eForm').formValidation();
      
        });

                            
    </script>
        @endsection