@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'categories']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Head</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Store <small> Categories</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Categories</h4>
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
                                        <th>Category</th>
                                        <th>Description</th>                                      
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $index => $category)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$category->category}}</td>
                                        <td>{{$category->description}}</td> 
                                        <td> @if(Auth::user()->hasRole('StoreManager'))
                                            <a class="btn btn-inverse btn-xs m-r-5" title="Click here to edit this category" href="{!! action('StoreControllerExtra@editCategory', base64_encode($category->category_id)) !!}"><i class="fa fa-edit"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                
                            </table>
                        @if(Auth::user()->hasRole('StoreManager'))         
                        <a class="btn btn-sm btn-primary m-b-10 hidden-print" href = "/store/categories/add/new" title="Click here to add new category"><i class="fa fa-plus"></i>  Add Category</a>   
                        @endif  
                               
                        </div> 
                         
                             
                             
                            
                         
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
 
        @endsection