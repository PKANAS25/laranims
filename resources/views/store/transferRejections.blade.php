@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'rejections']); ?> 

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
			<h1 class="page-header hidden-print">Store Rejections <small> {{$viewer}}</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Store Rejections</h4>
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
                                
                                <div><a class="btn btn-sm btn-warning" href="/store/main/rejections/unread">Rejections Unseen</a>
                                     &nbsp;&nbsp;&nbsp;&nbsp;
                                     <a class="btn btn-sm  btn-success" href="/store/main/rejections/read">Rejections Seen</a>
                                </div>

                              <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>No. of Items</th>
                                        <th>Transfered On</th>
                                        <th>Transfered By</th>
                                        <th>Rejected By</th> 
                                        <th>Reason</th>
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
                                <td>{{$transfer->rejected_by}}</td>
                                <td>

                                  @if($transfer->reject_read==0)
                                  
                                    <div id="divRead{{ $index}}">
                                        <button class="btn btn-sm btn-icon   btn-primary" id="rejRead{{ $index}}" value="{{ $transfer->transfer_id}}"> <i class="fa fa-eye"></i></button>
                                    </div>
                                    <script type="text/javascript">
                                            $(document.body).on('click', '#rejRead{{ $index}}', function(e){
                                                e.preventDefault();
                                                transferId = $(this).val();

                                                 $.get('/transferRejectRead',{transferId:transferId }, function(actionBlade){                      
                                                    $("#divRead{{ $index}}").html(actionBlade);
                                                     
                                                });
                                            });
                                    </script>        

                                  @else
                                  {{$transfer->rejection_reason}}
                                  @endif
                                </td>
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