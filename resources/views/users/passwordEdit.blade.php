@extends('formsMaster') 

@section('urlTitles')
<?php session(['title' => 'Administrator']);
session(['subtitle' => '']); ?>
@endsection


@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Users</a></li>
				<li class="active"><a href="javascript:;">Edit</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Change <small>  Password</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Password</h4>
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
 
             
                                             
             $('#eForm').formValidation({
                message: 'This value is not valid',
                

                fields: {
                              
                            
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

            

});     
             
          
                            
    </script>
        @endsection