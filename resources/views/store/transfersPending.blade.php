@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => '']); ?> 

@section('content') 

<link rel="stylesheet" type="text/css" href="/dist/msgbox/jquery.msgbox.css" />
<script type="text/javascript" src="/dist/msgbox/jquery.msgbox.min.js"></script> 

<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Branch</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Store Transfers <small> waiting approval</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Pending Transfers</h4>
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


                              <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>No. of Items</th>
                                        <th>Transfered On</th>
                                        <th>Transfered By</th>
                                        <th></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($transfers AS $index => $transfer)
                                <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$transfer->item_name}}</td>
                                <td>{{$transfer->count}}</td>
                                <td>{{$transfer->dated}}</td>
                                <td>{{$transfer->transfered_by}}</td>
                                <td><button class="btn btn-success btn-sm m-r-5" id="Approve{{$index+1}}" >Approve</button>
                                <script type="text/javascript">
                                    $('#Approve{{$index+1}}').click(function(ev) {
                                    
                                      $.msgbox("<p>This action will store {{$transfer->count}} nos. to your stock. Are you sure that you want to accept this transfer?</p>", {
                                        type    : "prompt",
                                         
                                        inputs  : [
                                          {type: "hidden", name: "ok", value: "ok"}
                                          
                                        ],
                                        buttons : [
                                          {type: "submit", name: "Approve", value: "Approve"},
                                          {type: "cancel", value: "Cancel"}
                                        ],
                                        form : {
                                          active: true,
                                          method: 'get',
                                          action: '{!! action('StoreController@approveTransfer', base64_encode($transfer->transfer_id)) !!}'
                                        }
                                      });
                                      
                                      ev.preventDefault();
                                    
                                    });
                                </script>
                                &nbsp;&nbsp;
                                <button class="btn btn-danger btn-sm m-r-5" id="Reject{{$index+1}}" >Reject</button>
                                <script type="text/javascript">
                                    $('#Reject{{$index+1}}').click(function(ev) {
                                    
                                      $.msgbox("<p>This action will restore these items to the main store. Are you sure that you want to reject this transfer?. Then please enter a reason for rejection</p>", {
                                        type    : "prompt",
                                         
                                        inputs  : [
                                          {type: "text", name: "reject_reason", value: "", required: true}
                                          
                                        ],
                                        buttons : [
                                          {type: "submit", name: "Reject", value: "Reject"},
                                          {type: "cancel", value: "Cancel"}
                                        ],
                                        form : {
                                          active: true,
                                          method: 'get',
                                          action: '{!! action('StoreController@rejectTransfer', base64_encode($transfer->transfer_id)) !!}'
                                        }
                                      });
                                      
                                      ev.preventDefault();
                                    
                                    });
                                </script></td>
                                </tr>
                                @endforeach                                
                                </tbody>
                            </table>

                               
                        </div> 
                          
                         
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
 
        @endsection