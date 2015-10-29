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
			<h1 class="page-header">Edit <small>  User</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Edit</h4>
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
                                    <label class="control-label col-md-4 col-sm-4">Type :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control"   id="admin_type" name="admin_type" data-fv-notempty="true">
                                            <option value="{{$user->admin_type}}">{{ ($user->admin_type>1) ? "Office Staff" : "Nursery Admin" }}</option>
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
                                                @if($user->admin_type>1)
                                                <option value="0">All Branches</option>
                                                @else
                                                <option value="{{ $user->branch }}">{{ $user->branch_name }}</option>
                                                @foreach($branches AS $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
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

            $('#eForm').formValidation()
.on('change', '[name="admin_type"]', function(e) {
         branchLoader();
      }); 
 

});     
             
          
                            
    </script>
        @endsection