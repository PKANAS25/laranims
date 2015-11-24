@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'suppliers']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Suppliers</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Store <small> Suppliers</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Suppliers List</h4>
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
                                        <th>Supplier</th>
                                        <th>Mobile</th>
                                        <th>Tel</th>
                                        <th>Email</th>                                        
                                        <th>Contact Person</th>
                                        <th>Business Volume</th> 
                                        <th>Mob II</th>
                                        <th>Tel II</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suppliers as $index => $supplier)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$supplier->name}}</td>
                                        <td>{{$supplier->mob1}}</td>
                                        <td>{{$supplier->ofc1}}</td>
                                        <td>{{$supplier->email}}</td>
                                        <td><a title="{{$supplier->address}}">{{$supplier->contact_person}}</a></td>
                                        <td>{{$supplier->volume+0}}</td> 
                                        <td>{{$supplier->mob2}}</td> 
                                        <td>{{$supplier->ofc2}}</td> 
                                        <td> @if(Auth::user()->hasRole('StoreManager'))
                                            <a class="btn btn-inverse btn-xs m-r-5" title="Click here to edit this supplier"  href="{!! action('StoreControllerExtra@editSupplier', base64_encode($supplier->supplier_id)) !!}" ><i class="fa fa-edit"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                
                            </table>
                             @if(Auth::user()->hasRole('StoreManager')) 
                            <a class="btn btn-sm btn-primary m-b-10 hidden-print" href = "/store/suppliers/add/new" title="Click here to add new supplier"><i class="fa fa-plus"></i>  Add Supplier</a>
                            @endif    
                        </div> 
                         
                             
                             
                            
                         
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
 
        @endsection