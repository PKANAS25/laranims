@extends('formsMaster') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'RequestsNTransfers']); ?> 

@section('content') 
<link href="/css/invoice-print.min.css" rel="stylesheet" />  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Requests and Transfers</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Store <small> Requests and Transfers</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Transfers against requests</h4>
                        </div>
                         
                        <div class="panel-body">         
                                <div class="hidden-print">
                                     @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}   
                                        </div>
                                    @endif
                                </div>    
 
                          

                            <form class="form-inline hidden-print" name="eForm" id="eForm"  method="POST" autocomplete="OFF">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <div class="form-group m-r-10">
                                    <select class="form-control" id="select-required" name="branch" data-fv-notempty="true">
                                            <option value="">Please choose</option>
                                            @foreach($branches as $branch)
                                            <option value="{!! $branch->id !!}">{!! $branch->name !!}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group m-r-10">
                                    <input class="form-control" type="text" placeholder="Start Date" id="start_date" name="start_date"  data-fv-notempty="true"  value="{{ old('start_date') }}" />
                                </div>
                                <div class="form-group m-r-10">
                                    <input class="form-control" type="text" placeholder="End Date" id="end_date" name="end_date"  data-fv-notempty="true"  value="{{ old('end_date') }}" />
                                </div>
                                 
                                <button type="submit" class="btn btn-primary m-r-5">Generate Report</button> 
                            </form> 
                             
                            
                           {{-- <a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-success m-b-10 hidden-print"><i class="fa fa-print m-r-5"></i> Print</a> --}}
                        </div>

                        @if($submit==1)
                        <div class="invoice-header onlyprintCenter">
                                    <div class="invoice-from" >
                                         
                                        <address class="m-t-5 m-b-5" align="center">
                                          <img src="/img/logo.jpg" width="65" height="65" alt=""> Store Requests And Transfers 
                                        </address>
                                    </div>
                                  
                                </div>
                        <div class="panel-body">  
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="nosort">#</th>
                                        <th>Item</th>
                                        <th>Total Request</th>
                                        <th>Transfered</th>
                                        <th>Pending</th>
                                        <th>Rejected</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requestItems AS $index => $requestItem)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$requestItem->item_name.$requestItem->new_item}}</td>
                                        <td>{{$requestItem->total_qty}}</td>
                                        <td>{{$requestItem->transferApproved}}</td>
                                        <td>{{$requestItem->transferPending}}</td>
                                        <td>{{$requestItem->transferRejected}}</td>
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