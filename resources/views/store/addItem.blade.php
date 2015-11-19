@extends('formsMaster') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'addItem']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Add Item</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Store <small> Add Item</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Add New Item</h4>
                        </div>
                        <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
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

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="item_name">Item Name :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="item_name" name="item_name" data-fv-notempty="true"    value="{{ old('item_name') }}" />  
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Category :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control" id="select-required" name="category" data-fv-notempty="true">
                                            <option value="">Please choose</option>
                                            @foreach($categories as $category)
                                            <option value="{!! $category->category_id !!}">{!! $category->category !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 

                            

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="product_code">Product Code :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text"   id="product_code" name="product_code"   data-fv-notempty="true" value="{{ old('product_code') }}" />  
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="price">Price :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="price"   name="price" data-fv-notempty="true"  min="1"  value="{{ old('price') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="description">Description :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="description"   name="description"    value="{{ old('description') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="fileToUpload">Pic</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="file"  accept="image/*"    data-fv-file="true"  data-fv-file-extension="jpeg,jpg"  data-fv-file-type="image/jpeg,image/jpg"  data-fv-file-maxsize="629760" data-fv-file-message="The selected file is not valid" id="fileToUpload" name="fileToUpload" /> <span class="text-info">Max size 500 Kb, JPG only</span>
                                    </div>
                                </div>
                               
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="reset" class="btn btn-sm btn-error">Reset</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                               
                        </div> 
                         
                             
                             
                             </form>
                         
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
  <script>
        $(document).ready(function() {
            App.init(); 
                                             
        //$('#eForm').formValidation();  

          });             
    </script>

        @endsection