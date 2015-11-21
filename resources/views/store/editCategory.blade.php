@extends('formsMaster') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'categories']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Edit Category</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Store <small> Edit Category</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">{{$category->category}}</h4>
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
                                    <label class="control-label col-md-4 col-sm-4" for="category">Category :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="category" name="category" data-fv-notempty="true"  data-fv-remote="true"  value="{{ $category->category }}" />  
                                    </div>
                                </div>

                                
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="description">Description :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="description"   name="description"    value="{{ $category->description }}" />
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
                    category: {
                     threshold: 5,
                     verbose: false,
                     
                     validators: {
                     
                     notEmpty: {},
                     remote: {
                        url: '/categoryEditCheck' ,
                        data: function(validator, $field, value) {
                            return {                                 
                                categoryId: {{$category->category_id}}
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

        if (data.field === 'category'
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
                .find('small[data-fv-validator="remote"][data-fv-for="category"]')
                    .show();
        }


        if (data.field === 'category'
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
                .find('small[data-fv-validator="remote"][data-fv-for="category"]')
                    .show();
        }

      

    })
    // This event will be triggered when the field doesn't pass given validator
    .on('err.validator.fv', function(e, data) { 
         
        // We need to remove has-warning class
        // when the field doesn't pass any validator
        if (data.field === 'category') {
            data.element
                .closest('.form-group')
                .removeClass('has-warning')
                  

        }
 
    });     

    });
       
    </script>

        @endsection