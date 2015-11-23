@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'exchanged']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Exchanged Items</a></li>
				 
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
                            <h4 class="panel-title">Items Exchanged</h4>
                        </div>
                        
                             
                        <div class="panel-body">
                                
                                <div class="hidden-print">
                                     
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
                                        <th>Receipt</th>
                                        <th width="15%">Receipt Date</th> 
                                        <th width="30%">Student</th>  
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exchangedItems as $index => $exchangedItem)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td><a target="blank" href="{!! action('InvoiceController@view', base64_encode($exchangedItem->invoice_id)) !!}">{{$exchangedItem->invoice_no}}</a></td>
                                        <td>{{$exchangedItem->inv_date}}</td> 
                                        <td>{{$exchangedItem->full_name}}</td> 
                                        <td>{{$exchangedItem->oldItemName}} has been repalced with {{$exchangedItem->newItemName}} on {{ $exchangedItem->dated  }}  by  {{$exchangedItem->adminer}}</td>
                                         
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