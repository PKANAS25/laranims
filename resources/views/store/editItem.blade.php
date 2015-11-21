@extends('formsMaster') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'addItem']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Edit Item</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Store <small> Edit Item</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">{{$item->item_name}}</h4>
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
                                        <input class="form-control" type="text" id="item_name" name="item_name" data-fv-notempty="true"  data-fv-remote="true"  value="{{ $item->item_name }}" />  
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Category :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control" id="select-required" name="category" data-fv-notempty="true">
                                            <option value="{{$item->category}}">{{$item->category_name}}</option>
                                            @foreach($categories as $category)
                                            <option value="{!! $category->category_id !!}">{!! $category->category !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 

                            

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="product_code">Product Code :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text"   id="product_code" name="product_code"   data-fv-notempty="true" value="{{ $item->product_code }}" />  
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="price">Price :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="price"   name="price" data-fv-notempty="true"  min="1"  value="{{ $item->price }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="description">Description :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="description"   name="description"    value="{{ $item->description }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="fileToUpload">Pic</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="file"  accept="image/*"    data-fv-file="true"  data-fv-file-extension="jpeg,jpg"  data-fv-file-type="image/jpeg,image/jpg"  data-fv-file-maxsize="629760" data-fv-file-message="The selected file is not valid" id="fileToUpload" name="fileToUpload" /> <span class="text-info">Max size 500 Kb, JPG only</span> 
                                            @if($pic)<div class="profile-image"><img src="{{$pic}}" /><i class="fa fa-user hide"></i></div>@endif
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
        $('#eForm').formValidation({
                message: 'This value is not valid',
                 fields: 
                 {
                    item_name: {
                     threshold: 5,
                     verbose: false,
                     
                     validators: {
                     
                     notEmpty: {},
                     remote: {
                        url: '/itemEditCheck' ,
                        data: function(validator, $field, value) {
                            return {                                 
                                itemId: {{$item->item_id}}
                            };
                        }

                    }
                }
            }
                 }
            
          })
        .on('success.validator.fv', function(e, data) {
        // data.field     --> The field name
        // data.element   --> The field element
        // data.result    --> The result returned by the validator
        // data.validator --> The validator name

        if (data.field === 'item_name'
            && data.validator === 'remote'
            && (data.result.available === false || data.result.available === 'false'))
        {

            // The userName field passes the remote validator
            data.element                    // Get the field element
                .closest('.form-group')     // Get the field parent

                // Add has-warning class
                .removeClass('has-success')
                .addClass('has-warning')

                // Show message
                .find('small[data-fv-validator="remote"][data-fv-for="item_name"]')
                    .show();
        }


        if (data.field === 'item_name'
            && data.validator === 'remote'
            && (data.result.available === true || data.result.available === 'true'))
        {
             
            // The userName field passes the remote validator
            data.element                    // Get the field element
                .closest('.form-group')     // Get the field parent

                // Add has-warning class
                .removeClass('has-warning')
                .addClass('has-success')

                // Show message
                .find('small[data-fv-validator="remote"][data-fv-for="item_name"]')
                    .show();
        }

      

    })
    // This event will be triggered when the field doesn't pass given validator
    .on('err.validator.fv', function(e, data) { 
         
        // We need to remove has-warning class
        // when the field doesn't pass any validator
        if (data.field === 'item_name') {
            data.element
                .closest('.form-group')
                .removeClass('has-warning')
                  

        }
 
    });     

    });
       
    </script>

        @endsection