@extends('master') 

@section('urlTitles')
<?php session(['title' => 'HR']);
session(['subtitle' => 'EmpSearchAll']); ?>
@endsection


@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Employees</a></li>
				<li class="active"><a href="javascript:;">Search</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Search <small>all employees</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Search</h4>
                        </div>
                        <div class="panel-body">

                             
                            <form name="eForm" id="eForm"  method="GET" autocomplete="OFF" class="form-inline hidden-print"  enctype="multipart/form-data"  data-fv-framework="bootstrap"
    data-fv-message="Required Field"
   
    data-fv-icon-invalid="glyphicon glyphicon-remove"
    data-fv-icon-validating="glyphicon glyphicon-refresh">

                                

                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                <fieldset>
                                    
                                     

                                <div class="form-group m-r-10">
                                    
                                        <input class="form-control" size="50" type="text" id="keyword" name="keyword"  placeholder="Name / Id / Mobile"    />
                                      
                                </div>

                                <div class="form-group m-r-10">
                                    
                                        <a class="btn btn-sm btn-warning" id="noLC" >No Labour card</a>
                                      
                                </div>
                                <div class="form-group m-r-10" align="right">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="badge badge-danger">Terminated</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="badge badge-warning">Resigned</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="badge badge-inverse">Deleted</span>
                                      
                                </div>
                                <br> &nbsp;&nbsp;

                                 <div id="searchResults"> </div>

                                    <script type="text/javascript">

 
                                    $('#keyword').keyup(function(e){  
                             
                                        e.preventDefault();
                                        var value =($(this).val());

                                        var data = {thisConfirm : $(this).val()};
                                        clearTimeout($(this).data('timer'));

                                         $(this).data('timer', setTimeout(function() {
                                            $.get('/employeeSearchBindAll',{keyword:value }, function(searchBlade){                      
                                            $("#searchResults").html(searchBlade);
                                            });
                                        },100));
                        
                                     }); 

                                    $(document.body).on('click', '#noLC', function(e){
                                          e.preventDefault();
                                        var value ="noLabourCardCheck";

                                        var data = {thisConfirm : $(this).val()};
                                        clearTimeout($(this).data('timer'));

                                         $(this).data('timer', setTimeout(function() {
                                            $.get('/employeeSearchBindAll',{keyword:value }, function(searchBlade){                      
                                            $("#searchResults").html(searchBlade);
                                            });
                                        },100));
                        
                                     }); 
                                   </script> 

                                </fieldset>
                            </form>

                              
                        </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
        @endsection