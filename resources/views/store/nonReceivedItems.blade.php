@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'nonReceived']); ?> 

@section('content') 
<link rel="stylesheet" type="text/css" href="/dist/msgbox/jquery.msgbox.css" />
<script type="text/javascript" src="/dist/msgbox/jquery.msgbox.min.js"></script>   
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Non received</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Store <small> Non received items</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Non received items</h4>
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

                                 <div><a class="btn btn-sm btn-warning" href="/store/students/nonreceived/waiting">Not Received</a>
                                     &nbsp;&nbsp;&nbsp;&nbsp;
                                     <a class="btn btn-sm  btn-success" href="/store/students/nonreceived/track">Receive Letter Issued</a>
                                </div>


                              <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Receipt</th>
                                        <th width="15%">Date</th>
                                        <th>Student</th>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th width="15%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if($viewer=="waiting")
                                    @foreach($nonReceivedItems AS $index => $nonReceivedItem)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$nonReceivedItem->invoice_no}}</td>
                                        <td>{{$nonReceivedItem->dated}}</td>
                                        <td>{{$nonReceivedItem->full_name}}</td>
                                        <td>{{$nonReceivedItem->item_name}}</td>
                                        <td>{{$nonReceivedItem->quantity_not_received}}</td>
                                        <td>@if($nonReceivedItem->stock < $nonReceivedItem->quantity_not_received) Insufficient Stock
                                            @else 
                                            <button class="btn btn-danger btn-sm m-r-5" id="Receive{{$index+1}}" >Receive Letter</button>
                                <script type="text/javascript">
                                    $('#Receive{{$index+1}}').click(function(ev) {
                                    
                                      $.msgbox("<p>This action results stock update. Make sure you get parents sign on receive letter. This action is irreversible. Do you want to continue?</p>", {
                                        type    : "prompt",
                                         
                                        inputs  : [
                                          {type: "hidden", name: "ok", value: "ok"}
                                          
                                        ],
                                        buttons : [
                                          {type: "submit", name: "Issue", value: "Yes"},
                                          {type: "cancel", value: "No"}
                                        ],
                                        form : {
                                          active: true,
                                          method: 'get',
                                          action: '{!! action('StoreControllerExtra@issueReceiveLetter', base64_encode($nonReceivedItem->custom_id)) !!}'
                                        }
                                      });
                                      
                                      ev.preventDefault();
                                    
                                    });
                                </script> 
                                            @endif</td>
                                    </tr>
                                    @endforeach

                                @else
                                    @foreach($nonReceivedItems AS $index => $nonReceivedItem)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$nonReceivedItem->invoice_no}}</td>
                                        <td>{{$nonReceivedItem->dated}}</td>
                                        <td>{{$nonReceivedItem->full_name}}</td>
                                        <td>{{$nonReceivedItem->item_name}}</td>
                                        <td>{{$nonReceivedItem->received}}</td>
                                        <td>{{$nonReceivedItem->receive_date}}</td>
                                    </tr>
                                    @endforeach                             
                                @endif
                                </tbody>
                            </table>

                              
                        </div> 
                         
                             
                             
                             
                         
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
 
        @endsection