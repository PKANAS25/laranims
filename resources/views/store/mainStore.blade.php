@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'main']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Head</a></li>
				 
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
                            <h4 class="panel-title">Items and Stock</h4>
                        </div>
                        
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
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
                                        <th>Product Code</th>
                                        <th>Price</th>
                                        <th>Stock</th>                                        
                                        <th>Pending</th>
                                        <th>Category</th> 
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Items as $index => $item)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td><a href="{!! action('StoreController@itemView', base64_encode($item->item_id)) !!}">{{$item->item_name}}</a></td>
                                        <td>{{$item->product_code}}</td>
                                        <td>{{$item->price}}</td>
                                        <td>{{$item->stock}}</td>
                                        <td>{{$item->pending}}</td>
                                        <td>{{$item->category_name}}</td> 
                                        <td> @if(Auth::user()->hasRole('StoreManager'))
                                            <a class="btn btn-inverse btn-xs m-r-5" title="Click here to edit this item" href="{!! action('StoreControllerExtra@editItem', base64_encode($item->item_id)) !!}"><i class="fa fa-edit"></i></a>
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