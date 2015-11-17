@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'returns']); ?> 

@section('content') 

<link rel="stylesheet" type="text/css" href="/dist/msgbox/jquery.msgbox.css" />
<script type="text/javascript" src="/dist/msgbox/jquery.msgbox.min.js"></script> 

<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Main</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Store Returns <small> {{$viewer}}</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Store Returns</h4>
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
                                
                                <div>
                                    
                                    <a class="btn btn-sm btn-warning" href="/store/main/returns/pending">Pending</a>
                                     &nbsp;&nbsp;&nbsp;&nbsp;
                                     <a class="btn btn-sm  btn-success" href="/store/main/returns/approved">Approved</a>
                                      &nbsp;&nbsp;&nbsp;&nbsp;
                                     <a class="btn btn-sm  btn-inverse" href="/store/main/returns/rejected">Rejected</a>
                                </div>
                                
                              <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Branch</th>
                                        <th>No. of Items</th>
                                        <th>Transfered On</th>
                                        <th>Transfered By</th>
                                        <th>Approval</th> 
                                        @if($viewer=='rejected')<th>Reason</th>@endif
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($returns AS $index => $return)
                                <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$return->item_name}}</td>
                                <td>{{$return->branch_name}}</td>
                                <td>{{$return->count}}</td>
                                <td>{{$return->dated}}</td>
                                <td>{{$return->transfered_by}}</td> 
                                <td>
                                  @if($return->approval==0) 
                                  <button class="btn btn-sm btn-success"  id="Approve{{$index+1}}">Approve</button>
                                                <script type="text/javascript">                                                
                                                    $('#Approve{{$index+1}}').click(function(ev) {
                                                    
                                                      $.msgbox("<p>This action will store these items to main store. Are you sure that you want to accept this transfer?</p>", {
                                                        type    : "prompt",
                                                         inputs  : [
                                                          {type: "hidden", name: "no", value: "no"} 
                                                        ],
                                                         
                                                        buttons : [
                                                          {type: "submit", name: "Approve", value: "Approve"},
                                                          {type: "cancel", value: "Cancel"}
                                                        ],
                                                        form : {
                                                          active: true,
                                                          method: 'get',
                                                          action: '{!! action('StoreController@itemReturnApprove', base64_encode($return->return_id)) !!}'
                                                        }
                                                      });
                                                      
                                                      ev.preventDefault();
                                                    
                                                    });
                                                </script>
                                    &nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-sm btn-danger" id="Reject{{$index+1}}">Reject</button>
                                                <script type="text/javascript">                                                
                                                    $('#Reject{{$index+1}}').click(function(ev) {
                                                    
                                                      $.msgbox("<p>This action will restore these items to the branch store. Are you sure that you want to reject this transfer?. Then please enter a reason for rejection</p>", {
                                                        type    : "prompt",
                                                         inputs  : [
                                                          {type: "text", name: "rejection_reason", value: ""} 
                                                        ],
                                                         
                                                        buttons : [
                                                          {type: "submit", name: "Reject", value: "Reject"},
                                                          {type: "cancel", value: "Cancel"}
                                                        ],
                                                        form : {
                                                          active: true,
                                                          method: 'get',
                                                          action: '{!! action('StoreController@itemReturnReject', base64_encode($return->return_id)) !!}'
                                                        }
                                                      });
                                                      
                                                      ev.preventDefault();
                                                    
                                                    });
                                                </script>            
                                  @elseif($return->approval==1)
                                  <span class="text-success">{{$return->approved_by}} <i class="fa fa-check-circle-o"></i></span>
                                  @elseif($return->approval==-1)
                                  <span class="text-danger">{{$return->approved_by}} <i class="fa fa-times-circle"></i></span>
                                  @endif
                                </td>
                                @if($viewer=='rejected')<td>{{$return->rejection_reason}}</td>@endif
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