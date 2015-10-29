@extends('resetMaster')  

@section('content')
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="javascript:;">Reset</a></li>
                <li class="active"><a href="javascript:;">Password</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header">Reset <small>Password</small></h1>
            <!-- end page-header -->
            <!-- begin row -->
             <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                 
                                
                                
                            </div>
                            <h4 class="panel-title">New Password</h4>
                        </div>
                        <div class="panel-body">

                             
                            <form name="eForm" id="eForm"  method="POST"  action="/password/reset" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data"  data-fv-framework="bootstrap"
    data-fv-message="Required Field"
   
    data-fv-icon-invalid="glyphicon glyphicon-remove"
    data-fv-icon-validating="glyphicon glyphicon-refresh">

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach

                                {!! csrf_field() !!}
                <input type="hidden" name="token" value="{{ $token }}">

                                <fieldset>
                                    
                                     

                               
               <div class="form-group m-b-20">
                    <input type="email" name="email"  value="{{ old('email') }}" class="form-control input-lg" placeholder="Registered Email" data-fv-notempty="true">
                </div>

                <div class="form-group m-b-20">
                    <input type="password" name="password" class="form-control input-lg" placeholder="New Password" data-fv-notempty="true">
                </div>

                <div class="form-group m-b-20">
                    <input type="password" name="password_confirmation" class="form-control input-lg" placeholder="Confirm Password" data-fv-notempty="true" data-fv-identical="true"
                data-fv-identical-field="password"
                data-fv-identical-message="The password and its confirm are not the same" />
                </div>

                 <div class="form-group">
                    <button type="submit" class="btn btn-success btn-block btn-lg">
                        Reset Password
                    </button>
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
        
    }) ;

 

});     
             
          
                            
    </script>
        @endsection