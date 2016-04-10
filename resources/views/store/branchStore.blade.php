@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'branchStore']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Branch</a></li>
				 
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
                            <h4 class="panel-title">Items and Stock</h4>
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
                                        <th class="nosort">#</th>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Stock</th>   
                                        <th>Returned</th>                                     
                                        <th>Sold(NR)</th>
                                        <th>Category</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Items as $index => $item)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td><a href="{!! action('StoreController@itemViewBranch', base64_encode($item->item_id)) !!}">{{$item->item_name}}</a></td>
                                        <td>{{$item->price}}</td>
                                        <td>{{$item->stock}}</td>
                                        <td>{{$item->returned}} ({{$item->pending_returns}})</td>
                                        <td>{{$item->sold}} ({{$item->qnr}})</td>
                                        <td>{{$item->category_name}}</td> 
                                         
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