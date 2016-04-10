@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'storeRequests']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Requests</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Branch <small> Store</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Requests Made</h4>
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
                                     <th class="nosort">#</th>
                                     <th>Admin</th>
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
                                     <td>{{$request->request_date}}</td>
                                     <td>{{$request->notes}}</td>
                                     <td><a title="View Items" href="#modal-dialog{{$index+1}}" class="btn btn-sm 
                                        @if($request->read_status==1) btn-success
                                         @else btn-default 
                                         @endif" data-toggle="modal"><i class="fa fa-eye text-inverse"></i></a>
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
                                     </td>
                                 </tr>
                                 @endforeach
                                 </tbody>
                              </table>
                                 @if(Auth::user()->hasRole('BranchStore'))         
                         <a class="btn btn-primary btn-sm m-r-5" href = "{{action('StoreControllerExtra@addStoreRequest')}}" title="Click here to add new request"><i class="fa fa-plus"></i>  New Request</a>  
                        @endif  
                        </div> 
                         
                             
                            
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
 
        @endsection