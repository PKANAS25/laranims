@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'returnRejections']); ?> 

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
			<h1 class="page-header hidden-print">Return Rejections <small> {{$viewer}}</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Returns Rejections</h4>
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
                                        <th>Returned By</th>
                                        <th>Rejected By</th> 
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($returns AS $index => $return)
                                <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$return->item_name}}</td>
                                <td>{{$return->count}}</td>
                                <td>{{$return->dated}}</td>
                                <td>{{$return->transfered_by}}</td>
                                <td>{{$return->rejected_by}}</td>
                                <td>

                                  @if($return->reject_read==0)
                                  
                                    <div id="divRead{{ $index}}">
                                        <button class="btn btn-sm btn-icon   btn-default" id="rejRead{{ $index}}" value="{{ $return->return_id}}"> <i class="fa fa-eye text-inverse"></i></button>
                                    </div>
                                    <script type="text/javascript">
                                            $(document.body).on('click', '#rejRead{{ $index}}', function(e){
                                                e.preventDefault();
                                                returnId = $(this).val();

                                                 $.get('/returnRejectRead',{returnId:returnId }, function(actionBlade){                      
                                                    $("#divRead{{ $index}}").html(actionBlade);
                                                     
                                                });
                                            });
                                    </script>        

                                  @else
                                  {{$return->rejection_reason}}
                                  @endif
                                </td>
                                </tr>
                                @endforeach                                
                                </tbody>
                            </table>
                                    <a class="btn btn-sm btn-warning" href="/store/branch/returns/rejections/unread">Rejections Unseen</a>
                                     &nbsp;&nbsp;&nbsp;&nbsp;
                                     <a class="btn btn-sm  btn-success" href="/store/branch/returns/rejections/read">Rejections Seen</a>
                               
                        </div> 
                          
                         
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
 
        @endsection