@extends('formsMaster') 

@section('urlTitles')
<?php session(['title' => 'Administrator']);
session(['subtitle' => 'register']); ?>
@endsection


@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Users</a></li>
				<li class="active"><a href="javascript:;">Register</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Register <small> New User</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Register</h4>
                        </div>
                        <div class="panel-body">

                             
                            <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data"  data-fv-framework="bootstrap"
    data-fv-message="Required Field"
   
    data-fv-icon-invalid="glyphicon glyphicon-remove"
    data-fv-icon-validating="glyphicon glyphicon-refresh">

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach

                                @if (session('status'))
                                    <div class="alert alert-success">
                                     {{ session('status') }} 
                                    </div>
                                @endif

                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                <fieldset>
                                    
                                     

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="name">Name:</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text"   name="name"   data-fv-notempty="true"     value="{{ old('name') }}" />
                                        <div id="live" class=></div>  
                                    </div>
                                </div>

                                  

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Type :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control"   id="admin_type" name="admin_type" data-fv-notempty="true">
                                            <option value="">Please choose</option>
                                            <option value="1">Nursery Admin</option>
                                            <option value="2">Office Staff</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Assign Branch :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <div id="branchLoader">
                                            <select class="form-control"  name="branch" >
                                                <option value="">Please choose</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="email">Email :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text"  name="email"  data-fv-notempty="true"  data-fv-remote="true" value="{{ old('email') }}"  />
                                        <span class="text-info">Your email id is your username</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="password">Password :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="password"  name="password"   data-fv-notempty="true"   />
                                    </div>
                                </div>

                               <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="password_confirmation">Confirm Password :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="password"   name="password_confirmation"   data-fv-notempty="true"  data-fv-identical="true"
                data-fv-identical-field="password"
                data-fv-identical-message="The password and its confirm are not the same" />
                                    </div>
                                </div>

                                 


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="reset" class="btn btn-sm btn-error">Reset</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>



                                </fieldset>
                            </form>

                              <script>
                                

                                    
                                  


                                </script>
                        </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
    <script>
        $(document).ready(function() {
            App.init();

<!---------------------------------------------------==================--------------------------------------------- -->

  function branchLoader()
    {
       
      var admin_type = $("#admin_type").val(); 
       

       $.get('/branchLoader',{adminType:admin_type }, function(branchBlade){ 
              
              $("#branchLoader").html(branchBlade);
              $('#eForm').formValidation('revalidateField', 'admin_type');
              
          });
    }   

<!---------------------------------------------------==================--------------------------------------------- -->
             
                                             
            //$('#eForm').formValidation();

            $('#eForm').formValidation({
                message: 'This value is not valid',
                

                fields: {
                             email: 
                             {
                                 
                                validators: {
                                                notEmpty: {
                                                    message: 'The email address is required and can\'t be empty'
                                                },

                                                emailAddress: {
                                                    message: 'The input is not a valid email address'
                                                },
                                     
                                               remote: 
                                               {
                                                    url: '/duplicateCheck'
                                               }
                                            }
                            },
                            
                            password: 
                             {
                                validators: 
                                {
                                     callback: 
                                     { 
                                        message: 'The password must contain 6 characters. Should be alpha numeric',
                                        callback:function(value, validator, $field)
                                        {
                                                if (value.length < 6) 
                                                return false;

                                                if (value.search(/[0-9]/) < 0) 
                                                return false;

                                                if (value.search(/[A-Za-z]/) < 0) 
                                                return false;  

                                            return true;
                                        }
                                    }
                                 }
                             }
                        }
        
    })
.on('change', '[name="admin_type"]', function(e) {
         branchLoader();
      })
    // This event will be triggered when the field passes given validator
    .on('success.validator.fv', function(e, data) {
        // data.field     --> The field name
        // data.element   --> The field element
        // data.result    --> The result returned by the validator
        // data.validator --> The validator name

        if (data.field === 'email'
            && data.validator === 'remote'
            && (data.result.available === false || data.result.available === 'false'))
        {

            // The userName field passes the remote validator
            data.element                    // Get the field element
                .closest('.form-group')     // Get the field parent

                // Add has-warning class
                .removeClass('has-success')
                .addClass('has-danger')

                // Show message
                .find('small[data-fv-validator="remote"][data-fv-for="email"]')
                    .show();
        }


        if (data.field === 'email'
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
                
        }

        //------------------------------------------------------------------------------------

         


    })
    // This event will be triggered when the field doesn't pass given validator
    .on('err.validator.fv', function(e, data) { 
         
        // We need to remove has-warning class
        // when the field doesn't pass any validator
        if (data.field === 'email') {
            data.element
                .closest('.form-group')
                .removeClass('has-warning')
                  

        }

        
    });

 

});     
             
          
                            
    </script>
        @endsection