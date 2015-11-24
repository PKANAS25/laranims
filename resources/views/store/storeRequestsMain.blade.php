@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'storeRequestsMain']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Requests</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Main <small> Store</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Requests {{$viewer}}</h4>
                        </div>
                        
                        <div class="panel-body">
                              
                              

                              <table id="data-table" class="table table-striped table-bordered">
                                 <thead>
                                    <tr>
                                     <th>#</th>
                                     <th>Admin</th>
                                     <th>Branch</th>
                                     <th>Date</th>
                                     <th width="20%">Notes</th>
                                     <th></th>
                                 </tr>
                                 </thead>
                                 <tbody>
                                 @foreach($requests AS $index => $request)
                                 <tr>
                                     <td>{{$index+1}}</td>
                                     <td>{{$request->name}}</td>
                                     <td>{{$request->branch_name}}</td>
                                     <td>{{$request->request_date}}</td>
                                     <td>{{$request->notes}}</td>
                                     <td>
                                        @if($request->read_status==1)
                                        <a title="View Items" href="#modal-dialog{{$index+1}}" class="btn btn-sm btn-success" data-toggle="modal"><i class="fa fa-eye text-inverse"></i></a>
                                          <div class="modal fade" id="modal-dialog{{$index+1}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">Request List</h4>
                                                    </div>  
                                                        <div class="modal-body">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Item</th>
                                                                        <th>Quantity</th>
                                                                    </tr>
                                                                </thead>
                                                                @foreach($requestItems[$index] AS $requestItem)
                                                                    <tr>
                                                                        <td>{{$requestItem->item_name.$requestItem->new_item}}</td>
                                                                        <td>{{$requestItem->qty}}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </table>    
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Close</a>
                                                            
                                                        </div> 

                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div id="Read{{$index+1}}">
                                          <button class="btn btn-sm" id="requestRead{{$index+1}}" value="{{ $request->request_id}}"> <i class="fa fa-eye"></i></button>
                                          <script type="text/javascript">
                                            $(document.body).on('click', '#requestRead{{$index+1}}', function(e){
                                                e.preventDefault();
                                                requestId = $(this).val();

                                                 $.get('/store/requestReader',{requestId:requestId }, function(actionBlade){                      
                                                    $("#Read{{$index+1}}").html(actionBlade);
                                                     
                                                });
                                            });
                                            </script>
                                        </div>    
                                        @endif
                                     </td>
                                 </tr>
                                 @endforeach
                                 </tbody>
                              </table>
                                <a class="btn btn-sm btn-warning" href="/store/main/requests/unread">Requests Unseen</a>
                                     &nbsp;&nbsp;&nbsp;&nbsp;
                                     <a class="btn btn-sm  btn-success" href="/store/main/requests/read">Requests Seen</a>
                        </div> 
                         
                             
                            
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
 
        @endsection